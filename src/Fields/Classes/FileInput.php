<?php

namespace Afsakar\FormMaker\Fields\Classes;

use Afsakar\FormMaker\Fields\Contract\Field;
use Afsakar\FormMaker\Models\FormBuilderField;
use Filament\Forms;

class FileInput extends Field
{
    public array $options = [];

    public static function make(FormBuilderField $field): Forms\Components\Field
    {
        return Forms\Components\FileUpload::make(data_get($field, 'options.fieldId'))
            ->label(data_get($field, 'name'))
            ->columnSpan([
                'xs' => 1,
                'sm' => 1,
                'md' => data_get($field, 'options.column_span', 1),
                'lg' => data_get($field, 'options.column_span', 1),
                'xl' => data_get($field, 'options.column_span', 1),
                'default' => data_get($field, 'options.column_span', 1),
            ])
            ->id(data_get($field, 'options.htmlId'))
            ->acceptedFileTypes(data_get($field, 'options.accepted_file_types') ?? ['application/pdf'])
            ->maxSize(data_get($field, 'options.max_size', 5120))
            ->directory('form')
            ->storeFiles(false)
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

                return $value === $get($fieldId);
            })
            ->required(data_get($field, 'options.is_required', false));
    }
}
