<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gains', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('amount', 8, 2)->default(0.00);
            $table->string('nameGroup', 55)->nullable();
            $table->date('date');
            $table->integer('nbPersonnes');
            $table->double('gainIndividuel', 8, 2);
            $table->timestamps(); // This creates `created_at` and `updated_at` columns

            // Specify the storage engine as MyISAM
            $table->engine = 'MyISAM';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('gains');
    }
}
