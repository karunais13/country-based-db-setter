<?php

namespace Karu\DBConnectionSetter\Console;

use DBConnectionHelper;
use Illuminate\Database\Console\Seeds\SeedCommand;
use Symfony\Component\Console\Input\InputOption;

class CustomSeed extends SeedCommand
{

    private $_country;

    protected $name = 'db:seed-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed all database with records';


    public function __construct()
    {
        parent::__construct(app('db'));
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

    protected function getDatabase()
    {
        DBConnectionHelper::setDBConnection($this->_country);

        dump('Database Connection : '. config('database.connections.currentHost.database'));

        return 'currentHost';
    }

    protected function getOptions()
    {
        return [
            ['class', null, InputOption::VALUE_OPTIONAL, 'The class name of the root seeder', 'DatabaseSeeder'],

            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to seed'],

            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production'],

            ['country', null, InputOption::VALUE_OPTIONAL, 'Run seed for provided country'],
        ];
    }
}
