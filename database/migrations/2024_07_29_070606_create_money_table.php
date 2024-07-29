<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoneyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('money', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pseudo', 191);
            $table->bigInteger('id_pseudo');
            $table->double('amount', 8, 2)->default(0.00);
            $table->double('credit', 8, 2)->default(0.00);
            $table->double('debit', 8, 2)->default(0.00);
            $table->float('creditGain')->default(0);
            $table->timestamps(); // This creates `created_at` and `updated_at` columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('money');
    }
}
