<?php

namespace Afsakar\FormMaker\Fields\Contract;

use Afsakar\FormMaker\Models\FormBuilderField;

abstract class Field
{
    abstract public static function make(FormBuilderField $field): \Filament\Forms\Components\Field;
}
