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
        Schema::create('organization_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->bigInteger('max_photos')->default(10000);
            $table->bigInteger('max_storage_mb')->default(10240); // 10GB default
            $table->bigInteger('max_albums')->default(500);
            $table->bigInteger('max_members')->default(100);
            $table->boolean('unlimited_photos')->default(false);
            $table->boolean('unlimited_storage')->default(false);
            $table->boolean('unlimited_albums')->default(false);
            $table->boolean('unlimited_members')->default(false);
            $table->timestamps();
            
            $table->unique('organization_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_limits');
    }
};