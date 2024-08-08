<?php

namespace Afsakar\FormMaker\Models\Traits;

trait InteractsWithFormBuilderCollection
{
    abstract public static function getClassName(): string;

    abstract public static function getLabelColumn(): string;
}
