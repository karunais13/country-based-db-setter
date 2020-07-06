<?php

namespace Karu\DBConnectionSetter\Console;

use DBConnectionHelper;
use Illuminate\Queue\Console\RetryCommand;
use Illuminate\Queue\Worker;

class CustomQueueRetry extends RetryCommand
{
    public function __construct($worker, $cache)
    {
        $this->signature .= "{--country=0 : Work to run for particular country}";

        parent::__construct($worker, $cache);
    }

    public function handle()
    {
        try{
            if( $this->option('country') && $this->option('country') != '0' )
                $countries = [DBConnectionHelper::getCountry(strtoupper($this->option('country')), 'country_code')];
            else
                $countries = DBConnectionHelper::countryListing();

            foreach( $countries as $key => $country ){
                if( DBConnectionHelper::checkDBExists($country->country_code) ){
                    parent::handle();
                }
            }
        } catch(\Exception $e){
            dump('Country Code not found.');
        }
    }
}
