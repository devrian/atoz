<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no', 10)->unique();
            $table->unsignedInteger('transaction_id');
            $table->decimal('amount', 15, 2);
            $table->string('model_type');
            $table->tinyInteger('order_status')->default(1)->comment('1 = New, 2 = Success, 3 = Fail, 4 = Cancel');
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
        Schema::dropIfExists('orders');
    }
}
