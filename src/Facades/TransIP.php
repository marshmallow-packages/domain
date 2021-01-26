<?php

namespace Marshmallow\Domain\Facades;

class TransIP extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return \Marshmallow\Domain\TransIP::class;
    }
}
