<?php

namespace App\Console\Commands;

use App\Models\OutgoingMedicine;
use Illuminate\Console\Command;

class OutgoingUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'outgoing:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For update outgoing details after delete the category';

    /**
     * Execute the console command.
     */

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        \Log::info("Cron is working fine!");

        $outgoing = OutgoingMedicine::has('details', '=', 0)->with('details')->get();

        foreach($outgoing as $o) {
            $o->delete();
        }
    }
}
