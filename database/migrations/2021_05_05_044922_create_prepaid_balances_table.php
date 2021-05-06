<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrepaidBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prepaid_balances', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number', 12);
            $table->decimal('amount', 15, 2);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            $table->softDeletes('deleted_at');

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prepaid_balances');
    }
}
