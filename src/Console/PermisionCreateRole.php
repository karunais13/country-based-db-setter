<?php

namespace Karu\DBConnectionSetter\Console;

use Illuminate\Console\Command;

class PermisionCreateRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:create-role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Roles';

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
        $modelPath  = config('roles.model_path')."\Role";

        $countryCode = $this->ask('What is the Country Code ?');

        \DBConnectionHelper::setDBConnection($countryCode);

        $name = $this->ask('What is the Role Name');

        $role = $modelPath::create(['name' => $name]);

        if( $role )
            $this->info('Role Succesfully Created');
        else
            $this->error('Fail to create Role :'.$name);
    }
}
