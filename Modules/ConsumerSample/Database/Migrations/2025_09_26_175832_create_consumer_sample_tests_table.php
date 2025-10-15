<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsumerSampleTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumer_sample_tests', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('consumer_sample_data_id');
        $table->foreign('consumer_sample_data_id')->references('id')->on('consumer_sample_data')->onDelete('cascade');
        $table->string('test');
        $table->string('result')->nullable();
        $table->string('status')->default('pending');
        $table->integer('times')->default(0);
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
        Schema::dropIfExists('consumer_sample_tests');
    }
}
