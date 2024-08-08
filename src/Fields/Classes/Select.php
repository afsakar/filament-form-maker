<?php

namespace Afsakar\FormMaker\Fields\Classes;

use Afsakar\FormMaker\Fields\Contract\Field;
use Afsakar\FormMaker\Models\FormBuilderCollection;
use Afsakar\FormMaker\Models\FormBuilderField;
use Filament\Forms;

class Select extends Field
{
    public static function make(FormBuilderField $field): Forms\Components\Field
    {
        return Forms\Components\Select::make(data_get($field, 'options.fieldId'))
            ->hiddenLabel()
            ->placeholder(data_get($field, 'options.is_required', false) ? data_get($field, 'name') . ' *' : data_get($field, 'name'))
            ->columnSpan([
                'xs' => 1,
                'sm' => 1,
                'md' => data_get($field, 'options.column_span', 1),
                'lg' => data_get($field, 'options.column_span', 1),
                'xl' => data_get($field, 'options.column_span', 1),
                'default' => data_get($field, 'options.column_span', 1),
            ])
            ->id(data_get($field, 'options.htmlId'))
            ->reactive()
            ->searchable(data_get($field, 'options.is_multiple', false) || data_get($field, 'options.is_searchable', false))
            ->multiple(data_get($field, 'options.is_multiple', false))
            ->preload(data_get($field, 'options.is_multiple', false))
            ->options(self::getOptions($field))
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

    protected static function getOptions($field): array
    {
        $type = data_get($field, 'options.data_source');

        $collection = FormBuilderCollection::find($type);

        $options = [];

        if ($collection?->type === 'list') { // @phpstan-ignore-line
            collect($collection->values)->each(function ($value) use (&$options) { // @phpstan-ignore-line
                $options[$value['value']] = $value['label'];
            });
        } else {
            $model = $collection?->model; // @phpstan-ignore-line
            $label = $model::getLabelColumn();

            $options = $model::all()->pluck($label, $label)->toArray();
        }

        return $options;
    }
}
