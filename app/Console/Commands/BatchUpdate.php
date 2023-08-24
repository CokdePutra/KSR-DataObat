<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BatchUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will execute update automatically for expired date to set is_active = false';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \Log::info("Batch updated success");
        DB::table('batches')
        ->update([
            'is_active' => DB::raw('CASE WHEN expired_date < NOW() THEN 0 ELSE 1 END')
        ]);
    }
}
