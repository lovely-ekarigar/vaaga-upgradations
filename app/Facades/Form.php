<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Helpers\FormBuilder
 */
class Form extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'form';
    }
}
