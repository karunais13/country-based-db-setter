<?php
namespace Karu\DBConnectionSetter\Facades;

use Illuminate\Support\Facades\Facade;

class DBConnectionSetterFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Karu\DBConnectionSetter\Helpers\DBConnectionSetterHelper';
    }
}
