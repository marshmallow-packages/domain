<?php

namespace Marshmallow\Domain\Facades;

class Domain extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return \Marshmallow\Domain\Domain::class;
    }
}
