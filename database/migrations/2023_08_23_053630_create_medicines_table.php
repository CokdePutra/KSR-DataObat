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
        Schema::create('medicines', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('category_id', 50);
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->string('medicine_code', 30);
            $table->string('name', 100);
            // $table->integer('stock');
            $table->string('unit', 50);
            $table->string('image', 100)->default('assets/uploads/medicines/default.jpg');
            $table->text('description')->nullable();
            $table->string('user_id', 50);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->date('expired_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
