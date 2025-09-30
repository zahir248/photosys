<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->text('description')->nullable();
            $table->timestamps();
        });
        
        // Insert default system settings
        DB::table('system_settings')->insert([
            [
                'key' => 'default_max_photos',
                'value' => '1000',
                'description' => 'Default maximum photos per user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'default_max_storage_mb',
                'value' => '1024',
                'description' => 'Default maximum storage in MB per user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'default_max_albums',
                'value' => '50',
                'description' => 'Default maximum albums per user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'default_max_organizations',
                'value' => '10',
                'description' => 'Default maximum organizations per user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
