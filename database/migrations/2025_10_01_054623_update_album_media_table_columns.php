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
        Schema::table('album_media', function (Blueprint $table) {
            $table->renameColumn('photo_id', 'media_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('album_media', function (Blueprint $table) {
            $table->renameColumn('media_id', 'photo_id');
        });
    }
};
