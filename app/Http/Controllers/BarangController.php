<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
{
    $barangs = Barang::where('stok', '>', 0)->get();
    
    return response()->json($barangs);
}
}
