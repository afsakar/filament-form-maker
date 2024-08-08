<?php

namespace Afsakar\FormMaker\Enums;

enum FormStatus: string
{
    case CLOSED = 'closed';
    case OPEN = 'open';

    public function label(): string
    {
        return match ($this) {
            self::CLOSED => 'Kapatıldı',
            self::OPEN => 'Açık',
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
