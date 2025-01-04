<?php

namespace App\Console\Commands;

use App\Models\Check as ModelsCheck;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Check extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ModelsCheck::where('created_at', '<', Carbon::now()->subMinute())->delete();
    }
}
