<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('date_of_payment');//تاریخ پرداخت
            $table->string('Payment_status');// وضعیت پرداخت
            $table->string('cost');//هزینه
            $table->enum('status',['current_installments','Deferred_installments','Installments_paid'])->default('current_installments');
            $table->string('loan_id');//id وام
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installments');
    }
};
