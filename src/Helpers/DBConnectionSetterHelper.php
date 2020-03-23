<?php

namespace Karu\DBConnectionSetter\Helpers;

use Cache;
use DB;

class DBConnectionSetterHelper
{

    const DB_CONNECTION_CACHE_KEY = 'db-connection';

    public function setDBConnection($country)
    {
        $defaultConfig = require 'db.php';

        config(['database.connections.mysql.database' => strtolower($country).'_'.$defaultConfig['default-mysql']['database']]);

    }

    public function checkDBExists($country): ?bool
    {
        try {

            $this->setDBConnection($country);

            $dbExists = Cache::tags(self::DB_CONNECTION_CACHE_KEY)->remember($this->generateCacheKey($country), 365*86400, function(){
                return DB::table('information_schema.schemata')->where('schema_name', config('database.connections.mysql.database') )->exists();
            });

            if( !$dbExists )
                throw new \Exception('Database doesn\'t exists : '.config('database.connections.mysql.database'));

            return TRUE;

        } catch (\Exception $e) {

            Cache::tags(self::DB_CONNECTION_CACHE_KEY)->forget($this->generateCacheKey($country));

            dump($e->getMessage());

            return FALSE;
        }
    }

    private function generateCacheKey($country)
    {
        return self::DB_CONNECTION_CACHE_KEY. '_' .$country;
    }


    public function countryListing($cache=true)
    {
        return collect(config('country'));
    }

    public function getCountryTimezone($countryCode)
    {
        return $this->countryListing()->firstWhere('country_code', $countryCode)->timezone;
    }

    public function getCountryCurrency($countryCode)
    {
        return $this->countryListing()->firstWhere('country_code', $countryCode)->currency_code;
    }

    public function getCountry($keyword, $field)
    {
        return $this->countryListing()->where($field, $keyword)->first();
    }

}
