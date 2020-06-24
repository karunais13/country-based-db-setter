<?php

namespace Karu\DBConnectionSetter\Middleware;

use Closure;
use DBConnectionHelper;

class DBConnectionSetByRequest
{

    /**
     * @var string
     */
    private $country;

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->setCountry($request->country_code);

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

    private function setDBconfig()
    {
        DBConnectionHelper::setDBConnection($this->getCountry());
    }
}
