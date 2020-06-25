<?php

namespace Karu\DBConnectionSetter\Console;

use Illuminate\Console\Command;

class PermisionCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:create-permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Permission';

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
        $modelPath  = config('roles.model_path')."\Permission";

        $countryCode = $this->ask('What is the Country Code ?');

        \DBConnectionHelper::setDBConnection($countryCode);

        $name = $this->ask('What is the Permission Name');

        $role = $modelPath::create(['name' => $name]);

        if( $role )
            $this->info('Permission Succesfully Created');
        else
            $this->error('Fail to create Permission :'.$name);
    }
}
