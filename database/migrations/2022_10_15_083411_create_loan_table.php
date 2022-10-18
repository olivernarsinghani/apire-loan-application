<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->double('amount',8,2);
            $table->double('payble_amount',8,2)->nullable();
            $table->decimal('interest_rate', 3, 2)->comment('Like 1% to 5% for each month');
            $table->text('description')->nullable();
            $table->tinyInteger('loan_tenure');
            $table->enum('status',['pending','approved','rejected','paid'])->default('pending');
            $table->softDeletes();
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
        Schema::dropIfExists('loan');
    }
};
