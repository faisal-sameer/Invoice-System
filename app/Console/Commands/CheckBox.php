<?php

namespace App\Console\Commands;

use App\Jobs\CheckBox as JobsCheckBox;
use App\Models\SequenceBill;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CheckBox extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Box:Check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $new = new JobsCheckBox;
        dispatch($new);

        $this->info('Successfully sent daily quote to FA.');
    }
}
