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
            $table->foreignId('user_id')->nullable();                    
            $table->string("code",50)->unique()->nullable();
            $table->string("name",100)->nullable();
            $table->decimal("price",20,2)->unsigned()->default(0.00);
            $table->decimal("stock",20,2)->unsigned()->default(0.00);
            $table->decimal("sold",20,2)->unsigned()->default(0.00);
            $table->string("images")->default(json_encode(["default.png"]));
            
            $table->timestamps();
            $table->softDeletes();
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
