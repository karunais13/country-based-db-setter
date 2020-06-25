<?php

namespace Karu\DBConnectionSetter\Console;

use Illuminate\Console\Command;

class PermisionAssignRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:assign-role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign role to user';

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

        $userModel = config('roles.user_model.class');
        $userModelKey = config('roles.user_model.key');

        $countryCode = $this->ask('What is the Country Code ?');
        \DBConnectionHelper::setDBConnection($countryCode);

        $roleList = $modelPath::all()->pluck('name')->toArray();

        $role = $this->choice('What role you want to assign', $roleList, 1);

        $empCode = $this->ask('What is the User ID ?');

        $user = $userModel::where($userModelKey, $empCode)->first();
        if( !$user ){
            $this->error('User not found.');
            die;
        }
        $user->assignRole($role);

        $this->info('Role Succesfully Assigned to User '.$empCode);
    }
}
