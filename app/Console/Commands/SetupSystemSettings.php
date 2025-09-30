<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SystemSetting;

class SetupSystemSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:setup-settings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup default system settings for organization limits';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Setting up system settings...');
        
        $settings = [
            'default_org_max_photos' => 10000,
            'default_org_max_storage_mb' => 10240,
            'default_org_max_albums' => 500,
            'default_org_max_members' => 100,
        ];
        
        foreach ($settings as $key => $value) {
            $setting = SystemSetting::firstOrCreate(
                ['key' => $key],
                ['value' => $value, 'description' => "Default {$key}"]
            );
            $this->line("Setting {$key}: {$setting->value}");
        }
        
        $this->info('Done!');
        
        return 0;
    }
}
