<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('owner_id');
            $table->string('name');
            $table->string('image');
            $table->date('exp_date');
            $table->string('category');
            $table->string('contact_info');
            $table->string('quantity');
            $table->string('price');
            $table->date('discount_date_1');
            $table->string('discount_value_1');
            $table->date('discount_date_2');
            $table->string('discount_value_2');
            $table->date('discount_date_3');
            $table->string('discount_value_3');
            $table->integer('comments')->default(0);
            $table->text('description')->nullable();
            $table->integer('likes')->default(0);
            $table->integer('views')->default(0);
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
        Schema::dropIfExists('products');
    }
}
