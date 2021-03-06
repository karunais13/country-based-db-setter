<?php

namespace Karu\DBConnectionSetter\Helpers;

use Cache;
use DB;

class DBConnectionSetterHelper
{

    const DB_CONNECTION_CACHE_KEY = 'db-connection';

    public function setDBConnection($country)
    {
        config(['dbsetter.current_connection_country' => $country]);

        if( config('dbsetter.mysql') ) {
            DB::purge('currentHost');

            $db = strtolower($country) . '_' . config('connections.default-mysql.database');

            config(['database.connections.currentHost' => [
                'driver' => 'mysql',
                'host' => config('database.connections.mysql.host'),
                'port' => config('database.connections.mysql.port'),
                'database' => $db,
                'username' => config('database.connections.mysql.username'),
                'password' => config('database.connections.mysql.password'),
                'unix_socket' => config('database.connections.mysql.unix_socket'),
                'charset' => config('database.connections.mysql.charset'),
                'collation' => config('database.connections.mysql.collation'),
                'prefix' => config('database.connections.mysql.prefix'),
                'prefix_indexes' => config('database.connections.mysql.prefix_indexes'),
                'strict' => config('database.connections.mysql.strict'),
                'engine' => config('database.connections.mysql.engine'),
            ]]);


            config(['queue.failed.database' => 'currentHost']);

            DB::setDefaultConnection('currentHost');
        }


        if( config('dbsetter.mongodb') ) {
            DB::purge('mongodb');

            $mongoDb = strtolower($country) . '_' . config('connections.default-mongo.database');

            config(['database.connections.mongodb' => [
                'driver' => config('database.connections.mongodb.driver'),
                'host' => config('database.connections.mongodb.host'),
                'port' => config('database.connections.mongodb.port'),
                'database' => $mongoDb,
                'username' => config('database.connections.mongodb.username'),
                'password' => config('database.connections.mongodb.password'),
            ]]);
        }
    }

    public function setOtherDbConnection($country, $connection, $baseDb, $isInitial=false)
    {
        try {

            $country = strtolower($country);

            if( $isInitial ){
                $db = "{$country}_{$baseDb}";
            }else{
                $db = "{$baseDb}_{$country}";
            }

            DB::purge($connection);

            config(["database.connections.{$connection}.database" => $db]);

            DB::connection($connection)->getPdo();

            return TRUE;

        } catch (\Exception $e) {

            $msg = 'Database doesn\'t exists : '.config("database.connections.{$connection}.database");

            if( app()->runningInConsole() ){
                dump($msg);
                return FALSE;
            } else{
                throw new \Exception($msg);
            }
        }
    }


    public function checkDBExists($country): ?bool
    {
        try {

            $this->setDBConnection($country);

            $dbExists = $this->getCache(self::DB_CONNECTION_CACHE_KEY)->remember($this->generateCacheKey($country), 365*86400, function(){
                return DB::table('information_schema.schemata')->where('schema_name', config('database.connections.currentHost.database') )->exists();
            });

            if( !$dbExists )
                throw new \Exception('Database doesn\'t exists : '.config('database.connections.currentHost.database'));

            return TRUE;

        } catch (\Exception $e) {

            $this->getCache(self::DB_CONNECTION_CACHE_KEY)->forget($this->generateCacheKey($country));

            $msg = 'Database doesn\'t exists : '.config('database.connections.currentHost.database');

            if( app()->runningInConsole() ){
                dump($msg);
                return FALSE;
            } else{
                throw new \Exception($msg);
            }
        }
    }

    public function generateCacheKey($country)
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

    public function countryCacheTag()
    {
        return self::DB_CONNECTION_CACHE_KEY;
    }


    public function getCache($tag=null)
    {
        try{
            return Cache::tags($tag);
        } catch (\BadMethodCallException $e){
            return new \Illuminate\Cache\CacheManager(app());
        }
    }
}
