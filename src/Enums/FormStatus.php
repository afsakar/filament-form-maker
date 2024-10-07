<?php

namespace Afsakar\FormMaker\Enums;

enum FormStatus: string
{
    case CLOSED = 'closed';
    case OPEN = 'open';

    public function label(): string
    {
        return match ($this) {
            self::CLOSED => trans('filament-form-maker::form-maker.enums.form_status.closed'),
            self::OPEN => trans('filament-form-maker::form-maker.enums.form_status.open'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::CLOSED => 'success',
            self::OPEN => 'danger',
        };
    }
}
