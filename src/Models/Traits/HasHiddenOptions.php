<?php

namespace Afsakar\FormMaker\Models\Traits;

use Filament\Forms;

trait HasHiddenOptions
{
    protected static function hiddenFormBuilderLabels(): Forms\Components\Group
    {
        return Forms\Components\Group::make()
            ->statePath('options')
            ->schema([
                Forms\Components\Hidden::make('static_name')->default(null),
                Forms\Components\Hidden::make('background_color')->default(null),
                Forms\Components\Hidden::make('admin_ids')->default(null),
            ])
            ->columns(1);
    }

    protected static function hiddenFieldLabels(): Forms\Components\Group
    {
        return Forms\Components\Group::make()
            ->statePath('options')
            ->schema([
                Forms\Components\Hidden::make('placeholder')->default(null),
                Forms\Components\Hidden::make('hidden_label')->default(false),
                Forms\Components\Hidden::make('is_required')->default(true),
                Forms\Components\Hidden::make('is_multiple')->default(false),
                Forms\Components\Hidden::make('is_searchable')->default(false),
                Forms\Components\Hidden::make('htmlId')->default(str()->random(6)),
                Forms\Components\Hidden::make('fieldId')->default(str()->random(6)),
                Forms\Components\Hidden::make('field_type')->default('text'),
                Forms\Components\Hidden::make('column_span')->default(1),
                Forms\Components\Hidden::make('data_source'),
                Forms\Components\Hidden::make('helper_text')->default(null),
            ])
            ->columns(1);
    }
}
