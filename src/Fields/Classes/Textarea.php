<?php

namespace Afsakar\FormMaker\Fields\Classes;

use Afsakar\FormMaker\Fields\Contract\Field;
use Afsakar\FormMaker\Models\FormBuilderField;
use Filament\Forms;

class Textarea extends Field
{
    public static function make(FormBuilderField $field): \Filament\Forms\Components\Field
    {
        return Forms\Components\Textarea::make(data_get($field, 'options.fieldId'))
            ->label(data_get($field, 'name'))
            ->hiddenLabel(data_get($field, 'options.hidden_label', false))
            ->placeholder(data_get($field, 'options.hidden_label', false) ? (data_get($field, 'options.placeholder') !== null ? data_get($field, 'options.placeholder') : data_get($field, 'name')) : null)
            ->columnSpan([
                'xs' => 1,
                'sm' => 1,
                'md' => data_get($field, 'options.column_span', 1),
                'lg' => data_get($field, 'options.column_span', 1),
                'xl' => data_get($field, 'options.column_span', 1),
                'default' => data_get($field, 'options.column_span', 1),
            ])
            ->reactive()
            ->autosize()
            ->id(data_get($field, 'options.htmlId'))
            ->visible(function ($get) use ($field) {
                $visibilty = data_get($field, 'options.visibility');

                if (! data_get($visibilty, 'active')) {
                    return true;
                }

                $fieldId = data_get($visibilty, 'fieldId');

                $value = data_get($visibilty, 'values');

                if (! $value) {
                    return $get($fieldId);
                }

                return in_array($get($fieldId), $value);
            })
            ->helperText(data_get($field, 'options.helper_text', null))
            ->required(data_get($field, 'options.is_required', false));
    }
}
