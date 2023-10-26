<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_metas', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_id');
            $table->integer('category_id');
            $table->integer('product_id');
            $table->string('unit_price');
            $table->string('quantity');
            $table->integer('unit_id');
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
        Schema::dropIfExists('purchase_metas');
    }
};
