<?php

namespace Afsakar\FormMaker\Models\Traits;

use Afsakar\FormMaker\Models\FormBuilderCollection;
use Filament\Facades\Filament;
use Filament\Forms;

trait HasOptions
{
    use HasHiddenOptions;

    protected static function staticFieldOptions(?int $columns): Forms\Components\Grid
    {
        return Forms\Components\Grid::make()
            ->statePath('options')
            ->columns(2)
            ->schema(function () use ($columns) {
                $colspanOptions = [];

                foreach (range(1, $columns) as $column) {
                    if ($column !== $columns) {
                        $colspanOptions[$column] = $column . '/' . $columns;
                    }
                }

                $colspanOptions['full'] = trans('filament-form-maker::form-maker.resources.builder.options.static_fields.full_span');

                return [
                    Forms\Components\Grid::make()
                        ->schema([
                            Forms\Components\TextInput::make('static_name')
                                ->label(trans('filament-form-maker::form-maker.resources.builder.options.static_fields.notification_name'))
                                ->nullable(),
                            Forms\Components\TextInput::make('placeholder')
                                ->label(trans('filament-form-maker::form-maker.resources.builder.options.static_fields.placeholder'))
                                ->nullable(),
                            Forms\Components\Toggle::make('is_required')
                                ->label(trans('filament-form-maker::form-maker.resources.builder.options.static_fields.is_required'))
                                ->default(true),
                            Forms\Components\Toggle::make('hidden_label')
                                ->label(trans('filament-form-maker::form-maker.resources.builder.options.static_fields.hidden_label'))
                                ->default(false),
                            Forms\Components\Hidden::make('fieldId')
                                ->required()
                                ->default(str()->random(6))
                                ->label(trans('filament-form-maker::form-maker.resources.builder.options.static_fields.field_id')),
                            Forms\Components\Hidden::make('htmlId')
                                ->required()
                                ->default(str()->random(6))
                                ->label(trans('filament-form-maker::form-maker.resources.builder.options.static_fields.html_id')),
                        ]),
                    Forms\Components\Select::make('column_span')
                        ->label(trans('filament-form-maker::form-maker.resources.builder.options.static_fields.column_span'))
                        ->options($colspanOptions)
                        ->searchable()
                        ->default('1'),
                    Forms\Components\TextInput::make('helper_text')
                        ->label(trans('filament-form-maker::form-maker.resources.builder.options.static_fields.helper_text'))
                        ->nullable(),
                ];
            })
            ->columns(1);
    }

    protected static function staticFormBuilderOptions(): Forms\Components\Group
    {
        return Forms\Components\Group::make()
            ->statePath('options')
            ->schema([
                Forms\Components\TextInput::make('static_name')
                    ->label(trans('filament-form-maker::form-maker.resources.builder.options.notification_name'))
                    ->nullable(),
                Forms\Components\ColorPicker::make('background_color')
                    ->label(trans('filament-form-maker::form-maker.resources.builder.options.background_color'))
                    ->default('transparent'),
                Forms\Components\Select::make('admin_ids')
                    ->label(trans('filament-form-maker::form-maker.resources.builder.options.user_emails'))
                    ->hintIcon('tabler-info-circle', trans('filament-form-maker::form-maker.resources.builder.options.user_emails_hint'))
                    ->native(false)
                    ->searchable()
                    ->options(function () {
                        $userModel = Filament::auth()->getProvider()->getModel(); // @phpstan-ignore-line

                        return $userModel::all()->pluck('name', 'id')->toArray();
                    })
                    ->multiple()
                    ->nullable(),
                Forms\Components\Section::make(trans('filament-form-maker::form-maker.resources.builder.options.notifications.label'))
                    ->collapsed()
                    ->statePath('notifications')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(trans('filament-form-maker::form-maker.resources.builder.options.notifications.title'))
                            ->nullable(),
                        Forms\Components\Textarea::make('body')
                            ->label(trans('filament-form-maker::form-maker.resources.builder.options.notifications.body'))
                            ->nullable(),

                    ]),
            ]);
    }

    protected static function selectFieldOptions(): array
    {
        return [
            Forms\Components\Section::make(trans('filament-form-maker::form-maker.resources.builder.options.select_field.title'))
                ->collapsed()
                ->statePath('options')
                ->schema([
                    Forms\Components\Toggle::make('is_multiple')
                        ->label(trans('filament-form-maker::form-maker.resources.builder.options.select_field.is_multiple'))
                        ->default(false),
                    Forms\Components\Toggle::make('is_searchable')
                        ->label(trans('filament-form-maker::form-maker.resources.builder.options.select_field.is_searchable'))
                        ->default(false),
                    Forms\Components\Select::make('data_source')
                        ->label(trans('filament-form-maker::form-maker.resources.builder.options.select_field.data_source'))
                        ->native(false)
                        ->searchable()
                        ->preload()
                        ->options(FormBuilderCollection::pluck('name', 'id')->toArray())
                        ->required(),
                ]),
        ];
    }

    protected static function checkboxRadioFieldOptions(): array
    {
        return [
            Forms\Components\Section::make(trans('filament-form-maker::form-maker.resources.builder.options.checkbox_radio_field.title'))
                ->collapsed()
                ->statePath('options')
                ->schema([
                    Forms\Components\Select::make('data_source')
                        ->label(trans('filament-form-maker::form-maker.resources.builder.options.checkbox_radio_field.data_source'))
                        ->searchable()
                        ->preload()
                        ->options(FormBuilderCollection::pluck('name', 'id')->toArray())
                        ->required(),
                    Forms\Components\ToggleButtons::make('columns')
                        ->label('Sütun Sayısı')
                        ->inline()
                        ->options([
                            1 => trans('filament-form-maker::form-maker.resources.builder.options.checkbox_radio_field.one_column'),
                            2 => trans('filament-form-maker::form-maker.resources.builder.options.checkbox_radio_field.two_columns'),
                            3 => trans('filament-form-maker::form-maker.resources.builder.options.checkbox_radio_field.three_columns'),
                            4 => trans('filament-form-maker::form-maker.resources.builder.options.checkbox_radio_field.four_columns'),
                        ])
                        ->default(2),
                ]),
        ];
    }

    protected static function textFieldOptions(): array
    {
        return [
            Forms\Components\Section::make(trans('filament-form-maker::form-maker.resources.builder.options.text_field.title'))
                ->collapsed()
                ->statePath('options')
                ->schema([
                    Forms\Components\Select::make('field_type')
                        ->label(trans('filament-form-maker::form-maker.resources.builder.options.text_field.field_type'))
                        ->native(false)
                        ->searchable()
                        ->preload()
                        ->live()
                        ->options([
                            'text' => trans('filament-form-maker::form-maker.resources.builder.options.text_field.types.text'),
                            'email' => trans('filament-form-maker::form-maker.resources.builder.options.text_field.types.email'),
                            'url' => trans('filament-form-maker::form-maker.resources.builder.options.text_field.types.url'),
                            'number' => trans('filament-form-maker::form-maker.resources.builder.options.text_field.types.number'),
                        ])
                        ->default('text')
                        ->required(),
                    Forms\Components\Grid::make()
                        ->visible(fn ($get) => $get('field_type') === 'number')
                        ->schema([
                            Forms\Components\TextInput::make('max_value')
                                ->label(trans('filament-form-maker::form-maker.resources.builder.options.text_field.max_value'))
                                ->type('number')
                                ->nullable(),
                            Forms\Components\TextInput::make('min_value')
                                ->label(trans('filament-form-maker::form-maker.resources.builder.options.text_field.min_value'))
                                ->type('number')
                                ->nullable(),
                        ]),
                ]),
        ];
    }

    protected static function getFileFieldOptions(): array
    {
        return [
            Forms\Components\Section::make(trans('filament-form-maker::form-maker.resources.builder.options.file_field.title'))
                ->statePath('options')
                ->collapsed()
                ->schema([
                    Forms\Components\TextInput::make('max_size')
                        ->label(trans('filament-form-maker::form-maker.resources.builder.options.file_field.max_size'))
                        ->type('number')
                        ->default(5120)
                        ->hint(trans('filament-form-maker::form-maker.resources.builder.options.file_field.hint'))
                        ->suffix('KB')
                        ->nullable(),
                    Forms\Components\Select::make('accepted_file_types')
                        ->label(trans('filament-form-maker::form-maker.resources.builder.options.file_field.accepted_file_types'))
                        ->options([
                            'application/pdf' => 'PDF',
                            'application/msword' => 'Word',
                            'application/vnd.ms-excel' => 'Excel',
                            'application/vnd.ms-powerpoint' => 'PowerPoint',
                            'application/zip' => 'Zip',
                            'image/*' => trans('filament-form-maker::form-maker.resources.builder.options.file_field.image'),
                        ])
                        ->native(false)
                        ->searchable()
                        ->multiple()
                        ->required(),
                ]),
        ];
    }

    protected static function getConditionalFieldOptions($getFields): Forms\Components\Component
    {
        if (filled($getFields)) {
            $getFields = collect($getFields)
                ->pluck('fields')
                ->mapWithKeys(function (array $item) {
                    return $item;
                });
        }

        return Forms\Components\Section::make(trans('filament-form-maker::form-maker.resources.builder.options.visibility.title'))
            ->statePath('options.visibility')
            ->collapsed()
            ->schema([
                Forms\Components\Toggle::make('active')
                    ->live()
                    ->label(trans('filament-form-maker::form-maker.resources.builder.options.visibility.active')),

                Forms\Components\Select::make('fieldId')
                    ->label(trans('filament-form-maker::form-maker.resources.builder.options.visibility.fieldId'))
                    ->live()
                    ->searchable(false)
                    ->visible(fn ($get): bool => ! empty($get('active')))
                    ->required(fn ($get): bool => ! empty($get('active')))
                    ->native(false)
                    ->searchable()
                    ->options(optional($getFields)->select('name', 'options', 'type')->mapWithKeys(function ($field) {
                        return [$field['options']['fieldId'] => $field['name']];
                    })->toArray())
                    ->afterStateUpdated(fn ($get, $set) => $set('values', null)),

                Forms\Components\Select::make('values')
                    ->label(trans('filament-form-maker::form-maker.resources.builder.options.visibility.values'))
                    ->live()
                    ->searchable(false)
                    ->visible(fn ($get): bool => ! empty($get('active')) && optional($getFields)->where('options.fieldId', $get('fieldId'))->where('type', 'select')->isNotEmpty())
                    ->helperText(trans('filament-form-maker::form-maker.resources.builder.options.visibility.values_helper_text'))
                    ->native(false)
                    ->searchable()
                    ->multiple()
                    ->options(function ($get) use ($getFields) {
                        $getRelated = $getFields->where('options.fieldId', $get('fieldId'))->where('type', 'select')->first();

                        if ($get('fieldId') === null) {
                            return [];
                        }

                        if (! isset($getRelated['options']['data_source'])) {
                            return [];
                        }

                        $collection = FormBuilderCollection::find($getRelated['options']['data_source']);

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
                    }),
            ]);
    }
}
