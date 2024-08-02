<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Barang::create(['kode' => 'B001', 'nama' => 'Barang A', 'harga' => 10000, 'stok' => 5]);
        Barang::create(['kode' => 'B002', 'nama' => 'Barang B', 'harga' => 20000, 'stok' => 5]);
    }
}
