<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_transactions', function (Blueprint $table) {
            $table->id();
            $table->string("code",50)->unique()->nullable();
            $table->foreignId('user_id')->nullable();                    
            $table->foreignId('customer_id')->nullable();                    
            $table->decimal("total",20,2)->default(0)->unsigned();
            $table->decimal("quantity",20,2)->default(0)->unsigned();
            $table->decimal("grand_total",20,2)->default(0)->unsigned();

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
        Schema::dropIfExists('product_transactions');
    }
}
