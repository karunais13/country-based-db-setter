<?php

namespace Karu\DBConnectionSetter\Console;

use Illuminate\Console\Command;

class PermisionCreateDefaultRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:create-default-role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Default Roles';

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

        $defaultRole = config('roles.roles');

        foreach( $defaultRole as $keyt => $item ){
            if( !$modelPath::where('name', $item)->exists() )
                $role = $modelPath::create(['name' => $item]);
        }

        $this->info('Role Succesfully Created');
    }
}
