<?php

namespace Afsakar\FormMaker\Enums;

enum FieldTypes: string
{
    case TEXT = 'text';
    case PHONE = 'phone';
    case TEXTAREA = 'textarea';
    case SELECT = 'select';
    case FILE = 'file';
    case DATE = 'date';
    case CHECKBOX = 'checkbox';
    case RADIO = 'radio';

    public function label(): string
    {
        return match ($this) {
            self::TEXT => 'Metin',
            self::PHONE => 'Telefon',
            self::TEXTAREA => 'Uzun Metin',
            self::SELECT => 'Seçim Kutusu',
            self::FILE => 'Dosya',
            self::DATE => 'Tarih',
            self::CHECKBOX => 'Onay Kutusu',
            self::RADIO => 'Seçim Düğmesi',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $type) => [$type->value => $type->label()])
            ->toArray();
    }
}
