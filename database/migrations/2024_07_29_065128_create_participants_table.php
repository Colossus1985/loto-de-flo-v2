<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstName', 191);
            $table->string('lastName', 191);
            $table->string('nameGroup', 55)->nullable();
            $table->integer('group_id')->nullable();
            $table->string('pseudo', 191)->unique();
            $table->string('email', 191)->nullable();
            $table->string('tel', 191);
            $table->decimal('amount', 8, 2)->nullable();
            $table->decimal('totalAmount', 8, 2)->nullable();
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
        // Schema::dropIfExists('participants');
    }
}
