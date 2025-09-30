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
        Schema::create('user_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->bigInteger('max_photos')->default(1000);
            $table->bigInteger('max_storage_mb')->default(1024); // 1GB default
            $table->bigInteger('max_albums')->default(50);
            $table->bigInteger('max_organizations')->default(10);
            $table->boolean('unlimited_photos')->default(false);
            $table->boolean('unlimited_storage')->default(false);
            $table->boolean('unlimited_albums')->default(false);
            $table->boolean('unlimited_organizations')->default(false);
            $table->timestamps();
            
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_limits');
    }
};
