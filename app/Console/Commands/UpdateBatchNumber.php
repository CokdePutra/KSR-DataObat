<?php

namespace App\Console\Commands;

use App\Models\Batch;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class UpdateBatchNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch-number:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Existing Batch Number';

    /**
     * Execute the console command.
     */

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Update all batch number');

        $batches = Batch::all();

        foreach($batches as $batch) {
            $uuid = Str::uuid();
            $uuidWithoutHyphens = str_replace('-', '', $uuid->toString());

            Batch::where('id', $batch->id)->update([
                'batch_number' => 'BATCH-' . substr($uuidWithoutHyphens, 0, 20)
            ]);
        }
    }
}
