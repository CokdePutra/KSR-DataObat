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
        Schema::create('batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('medicine_id', 50);
            $table->foreign('medicine_id')->references('id')->on('medicines')->onDelete('cascade');
            $table->string('batch_number', 30);
            $table->integer('quantity');
            $table->date('expired_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
