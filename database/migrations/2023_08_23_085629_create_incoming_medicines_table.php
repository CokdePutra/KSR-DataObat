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
        Schema::create('incoming_medicines', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('batch_id', 50);
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
            $table->date('incoming_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_medicines');
    }
};
