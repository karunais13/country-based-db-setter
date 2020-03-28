<?php

namespace Karu\DBConnectionSetter\Console;

use DBConnectionHelper;
use Illuminate\Database\Console\Seeds\SeedCommand;

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
        if (! $this->confirmToProceed()) {
            return;
        }

        $countries = DBConnectionHelper::countryListing();
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

        dump('Database Connection : '. config('database.default'));

        return $this->laravel['config']['database.default'];
    }
}
