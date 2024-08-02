<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::create(['kode' => 'C001', 'name' => 'Customer A', 'telp' => '08123456789']);
        Customer::create(['kode' => 'C002', 'name' => 'Customer B', 'telp' => '08234567890']);
    }
}
