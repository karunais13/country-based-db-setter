<?php

namespace Karu\DBConnectionSetter\Middleware;

use Closure;
use Auth;
use DBConnectionHelper;

class DBConnectionSet
{

    /**
     * @var string
     */
    private $country = "";


    /**
     * @var bool
     */
    private $isApi = FALSE;

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $uri  = $request->route()->uri();
        if( strlen($uri) > 0 && in_array('api', explode('/', $uri)) )
            $this->setIsApi(TRUE);

        //API
        if( $this->isApi() ){
            $this->setCountry($request->country_code);
        }
        //WEB
        else{
            if( Auth::check() ) {

                if( empty(Auth::user()->country_code) ){
                    Auth::logout();
                    return redirect()->route('web.login');
                }

                $this->setCountry(Auth::user()->country_code);
            }
            else{
                redirect()->route('web.login');
            }

        }

        if( $this->getCountry() ) {
            $this->setDBConfig();
        }

        return $next($request);
    }

    /**
     * @return string
     */
    private function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    private function setCountry( string $country ): void
    {
        $this->country = strtoupper($country);
    }

    /**
     * @return bool
     */
    private function isApi(): bool
    {
        return $this->isApi;
    }

    /**
     * @param bool $isApi
     */
    private function setIsApi( bool $isApi ): void
    {
        $this->isApi = $isApi;
    }

    private function setDBconfig()
    {
        DBConnectionHelper::setDBConnection($this->getCountry());
    }
}
