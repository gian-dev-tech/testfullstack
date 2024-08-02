<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['saleDetails.barang', 'customer'])->get();

        $data = $sales->map(function ($sale) {

            $jumlah_barang = $sale->saleDetails->sum('qty');


            return [
                'no_transaksi' => $sale->kode,
                'tanggal' => $sale->tgl,
                'nama_customer' => $sale->customer->name ?? 'N/A',
                'jumlah_barang' => $jumlah_barang,
                'barang' => $sale->saleDetails->map(function ($detail) {
                    return [
                        'nama_barang' => $detail->barang->nama ?? 'N/A',
                        'subtotal' => $detail->total,
                    ];
                }),
                'subtotal' => $sale->subtotal,
                'diskon' => $sale->diskon,
                'ongkir' => $sale->ongkir,
                'total_bayar' => $sale->total_bayar,
            ];
        });

        $grand_total = $sales->sum('total_bayar');

        return view('sales.index', compact('data', 'grand_total'));
    }

    public function create()
    {
        return view('sales.create');
    }
    private function generateTransactionCode()
    {
        $lastSale = Sale::latest('id')->first();
        $lastCode = $lastSale ? $lastSale->kode : 'TRK0000000';

        $lastNumber = (int) substr($lastCode, 3);
        $newNumber = $lastNumber + 1;

        $newCode = 'TRK' . str_pad($newNumber, 7, '0', STR_PAD_LEFT);

        return $newCode;
    }

    public function store(Request $request)
{
    Log::info('Store request data: ', $request->all());

    // Validasi data request
    $request->validate([
        'cust_id' => 'required|exists:customers,id',
        'items.*.barang_id' => 'required|exists:barangs,id',
        'items.*.qty' => 'required|integer|min:1',
        'items.*.harga_bandrol' => 'required|numeric',
        'items.*.diskon_pct' => 'required|numeric',
        'items.*.diskon_nilai' => 'required|numeric',
        'items.*.harga_diskon' => 'required|numeric',
        'items.*.total' => 'required|numeric',
        'diskon' => 'required|numeric',
        'ongkir' => 'required|numeric',
    ]);

    $kode = $this->generateTransactionCode();

    // Hitung subtotal
    $subtotal = 0;
    foreach ($request->items as $item) {
        $subtotal += $item['total'];
    }

    // Hitung total_bayar
    $diskon = $request->diskon;
    $ongkir = $request->ongkir;
    $total_bayar = $subtotal - $diskon + $ongkir;

    DB::beginTransaction();

    try {
        $sale = Sale::create([
            'cust_id' => $request->cust_id,
            'kode' => $kode,
            'tgl' => $request->tgl,
            'subtotal' => $subtotal,
            'diskon' => $diskon,
            'ongkir' => $ongkir,
            'total_bayar' => $total_bayar,
        ]);

        foreach ($request->items as $item) {
            $barang = Barang::find($item['barang_id']);

            if ($barang->stok < $item['qty']) {
                DB::rollBack();
                return redirect()->back()->withErrors(['error' => 'Stok barang ' . $barang->nama . ' tidak mencukupi!']);
            }

            SaleDetail::create([
                'sale_id' => $sale->id,
                'barang_id' => $item['barang_id'],
                'qty' => $item['qty'],
                'harga_bandrol' => $item['harga_bandrol'],
                'diskon_pct' => $item['diskon_pct'],
                'diskon_nilai' => $item['diskon_nilai'],
                'harga_diskon' => $item['harga_diskon'],
                'total' => $item['total'],
            ]);

            $barang->stok -= $item['qty'];
            $barang->save();
        }

        DB::commit();
        return redirect()->back()->with('success', 'Sale successfully created!');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Transaction failed: ' . $e->getMessage());
        return redirect()->back()->withErrors(['error' => 'Transaction failed!']);
    }
}

}
