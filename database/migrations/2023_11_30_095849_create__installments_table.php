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
        Schema::create('_installments', function (Blueprint $table) {
            $table->id();
            $table->string('date_of_payment');//تاریخ پرداخت
            $table->string('Payment_status');// وضعیت پرداخت
            $table->string('cost');//هزینه
            $table->string('loan_id');//id وام
            $table->id('user_id');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_installments');
    }
};
