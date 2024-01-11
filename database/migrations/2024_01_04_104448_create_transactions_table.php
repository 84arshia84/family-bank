<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('Price');
            $table->json('gateway_result')->nullable();
            $table->bigInteger('loan_id')->nullable();
            $table->bigInteger('installment_id')->nullable();
            $table->enum('status', ['Pending', 'success', 'failed']);
            $table->enum('type', ['subscription', 'installment'])->default('installment');
            $table->date('date')->default( Carbon::now()  ); // تاریخ
            $table->string('tracking_code')->nullable(); // کد پیگیری
            $table->timestamps();
            $table->text('description')->nullable(); // توضیحات
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
