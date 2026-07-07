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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->foreignId('skin_id')->constrained()->restrictOnDelete();
            $table->string('buyer_name');
            $table->string('buyer_email');
            $table->string('buyer_whatsapp');
            $table->string('buyer_ml_nickname');
            $table->string('buyer_ml_id');
            $table->string('buyer_ml_server');
            $table->unsignedInteger('total_diamond');
            $table->unsignedInteger('total_rupiah');
            $table->enum('status', ['pending', 'success', 'canceled'])->default('pending');
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
