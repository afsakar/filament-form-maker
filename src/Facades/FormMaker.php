<?php

namespace Afsakar\FormMaker\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Afsakar\FormMaker\FormMaker
 */
class FormMaker extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Afsakar\FormMaker\FormMaker::class;
    }
}
