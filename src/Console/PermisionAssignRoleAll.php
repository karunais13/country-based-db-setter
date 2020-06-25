<?php

namespace Karu\DBConnectionSetter\Console;

use Illuminate\Console\Command;

class PermisionAssignRoleAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:assign-role-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign role to all user';

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
        $userModel = config('roles.user_model.class');

        $modelPath  = config('roles.model_path')."\Role";

        $countryCode = $this->ask('What is the Country Code ?');
        \DBConnectionHelper::setDBConnection($countryCode);

        $roleList = $modelPath::all()->pluck('name')->toArray();

        $role = $this->choice('What role you want to assign', $roleList, 1);

        $users = $userModel::all();

        foreach( $users as $user ){
            $user->assignRole($role);
        }

        $this->info('Role Succesfully Assigned to All User.');
    }
}
