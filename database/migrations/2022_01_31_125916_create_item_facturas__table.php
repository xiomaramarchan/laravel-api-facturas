<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_facturas_', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("factura_id")->unsigned()->index(); 
            $table->integer("item_id")->unsigned();            
            $table->integer('cantidad');  
            $table->float('precio_venta');          
            $table->float('valor_total');
            $table->timestamps();
            $table->foreign('factura_id')
            ->references('id')
            ->on('facturas')
            ->onDelete('CASCADE');
            $table->foreign('item_id')
            ->references('id')
            ->on('items')
            ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_facturas_');
    }
}
