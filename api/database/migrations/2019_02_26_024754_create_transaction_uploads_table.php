<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_upload', function (Blueprint $table) {
            $table->increments('id');
            $table->text('filepath');
            $table->enum('status', ['pending', 'processed']);
            $table->unsignedInteger('completed');
            $table->unsignedInteger('failed');
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
        Schema::dropIfExists('transaction_upload');
    }
}
