<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsumerSampleDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumer_sample_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('consumer_sample_id');
            $table->foreign('consumer_sample_id')->references('id')->on('consumer_samples')->onDelete('cascade');
            $table->string('reference_number')->nullable();
            $table->string('source')->nullable();
            $table->string('sample_locations')->nullable();
            $table->string('quantity')->nullable();
            $table->string('temperature')->nullable();
            $table->string('collected')->nullable();
            $table->string('weather_condition')->nullable();
            $table->string('testing_status')->default('Created');
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
        Schema::dropIfExists('consumer_sample_data');
    }
}
