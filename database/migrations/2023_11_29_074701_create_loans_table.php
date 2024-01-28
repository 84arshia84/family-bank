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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('title_of_loan');
            $table->string('amount');
            $table->longText('description');
            $table->enum('status',['Pending','accept','reject'])->default('Pending');
            $table->enum('payment_status',['paid','unpaid'])->default('unpaid');
            $table->timestamp('date_of_loan');
            $table->softDeletes(); // اضافه کردن ستون حذف نرم
            $table->integer('user_id');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }

};
