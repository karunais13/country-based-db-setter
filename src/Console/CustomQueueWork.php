<?php

namespace Karu\DBConnectionSetter\Console;

use DBConnectionHelper;
use Illuminate\Queue\Console\WorkCommand;

class CustomQueueWork extends WorkCommand
{
    public function handle()
    {
        $countries = DBConnectionHelper::countryListing();
        foreach( $countries as $key => $country ){
            if( DBConnectionHelper::checkDBExists($country->country_code) ){
                parent::handle();
            }
        }
    }
}
