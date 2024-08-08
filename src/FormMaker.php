<?php

namespace Afsakar\FormMaker;

class FormMaker
{
    public static function getSupportedLocales(): array
    {
        return config('filament-form-maker.translation.locales');
    }
}
