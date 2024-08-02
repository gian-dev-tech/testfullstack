<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
        $table->string('kode', 15)->unique();
        $table->dateTime('tgl');
        $table->foreignId('cust_id')->constrained('customers');
        $table->decimal('subtotal', 15, 2);
        $table->decimal('diskon', 15, 2);
        $table->decimal('ongkir', 15, 2);
        $table->decimal('total_bayar', 15, 2);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
