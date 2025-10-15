<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOwnSampleDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('own_sample_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('own_sample_id');
            $table->foreign('own_sample_id')->references('id')->on('own_samples')->onDelete('cascade');
            $table->string('reference_number')->nullable();
            $table->string('quantity')->nullable();
            $table->string('temperature')->nullable();
            $table->string('collected')->nullable();
            $table->string('weather_condition')->nullable();
            $table->string('testing_status')->default('Ongoing');
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
        Schema::dropIfExists('own_sample_data');
    }
}
