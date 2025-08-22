<?php

namespace malpaso\LaravelAxcelerate\Commands;

use Illuminate\Console\Command;
use malpaso\LaravelAxcelerate\LaravelAxcelerate;

class LaravelAxcelerateCommand extends Command
{
    public $signature = 'axcelerate:test';

    public $description = 'Test the Axcelerate API connection';

    public function handle(): int
    {
        $this->info('Testing Axcelerate API connection...');

        try {
            $axcelerate = app(LaravelAxcelerate::class);
            $response = $axcelerate->testConnection();

            $this->info('✅ Connection successful!');
            $this->line('Response: '.json_encode($response, JSON_PRETTY_PRINT));

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ Connection failed: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
