<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsumerSamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumer_samples', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->unsignedBigInteger('consumer_id');
            $table->foreign('consumer_id')->references('id')->on('consumers')->onDelete('cascade');
            $table->string('laboratory_no')->nullable();
            $table->double('transport')->default(0);
            $table->double('vat')->default(0);
            $table->double('paid_amount')->default(0);
            $table->string('payment_status');
            $table->double('subtotal')->default(0);
            $table->double('total_payment_amount')->default(0);
            $table->double('balance')->default(0);
            $table->string('status')->default('Mail not yet sent');
            $table->integer('sample_count');
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
        Schema::dropIfExists('consumer_samples');
    }
}
