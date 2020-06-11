<?php

namespace Karu\DBConnectionSetter\Console;

use Illuminate\Console\Command;

class ClearCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:connection-clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear DB connection cache';

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
     * @return mixed
     */
    public function handle()
    {

        \Cache::tags(\DBConnectionHelper::countryCacheTag())->flush();
        
        $this->info('Cache Cleared');
    }
}
