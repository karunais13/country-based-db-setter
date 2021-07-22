<?php

namespace Karu\DBConnectionSetter\Console;
use Illuminate\Database\Console\Migrations\MigrateCommand;
use DBConnectionHelper;

class CustomMigration extends MigrateCommand
{

    private $_country;

    protected $signature = 'migrate-all {--force : Force the operation to run when in production}
                {--country= : Run migration for provided country}                
                {--database= : The database connection to use}
                {--path= : The path to the migrations files to be executed}
                {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
                {--pretend : Dump the SQL queries that would be run}
                {--seed : Indicates if the seed task should be re-run}
                {--step : Force the migrations to be run so they can be rolled back individually}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the database migrations';


    public function __construct()
    {
        parent::__construct(app('migrator'), app('events'));
    }

    public function handle()
    {
        if( $this->option('country') ){
            $countries = DBConnectionHelper::countryListing()->where('country_code', $this->option('country'));
        }else{
            $countries = DBConnectionHelper::countryListing();
        }

        foreach( $countries as $key => $country ){
            if( DBConnectionHelper::checkDBExists($country->country_code) ){
                $this->_country = $country->country_code;

                parent::handle();
            }
        }




    }

    /**
     * Prepare the migration database for running.
     *
     * @return void
     */
    protected function prepareDatabase()
    {
        DBConnectionHelper::setDBConnection($this->_country);

        dump('Database Connection : '. config('database.connections.currentHost.database'));

        if (! $this->migrator->repositoryExists()) {

            $this->call('migrate:install', array_filter([
                '--database' => 'currentHost',
            ]));
        }
    }
}
