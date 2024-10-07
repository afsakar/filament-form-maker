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
            self::TEXT => trans('filament-form-maker::form-maker.enums.field_types.text'),
            self::PHONE => trans('filament-form-maker::form-maker.enums.field_types.phone'),
            self::TEXTAREA => trans('filament-form-maker::form-maker.enums.field_types.textarea'),
            self::SELECT => trans('filament-form-maker::form-maker.enums.field_types.select'),
            self::FILE => trans('filament-form-maker::form-maker.enums.field_types.file'),
            self::DATE => trans('filament-form-maker::form-maker.enums.field_types.date'),
            self::CHECKBOX => trans('filament-form-maker::form-maker.enums.field_types.checkbox'),
            self::RADIO => trans('filament-form-maker::form-maker.enums.field_types.radio'),
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $type) => [$type->value => $type->label()])
            ->toArray();
    }
}
