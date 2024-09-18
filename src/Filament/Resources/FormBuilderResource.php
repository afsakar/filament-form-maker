<?php

namespace Afsakar\FormMaker\Filament\Resources;

use Afsakar\FormMaker\Enums\FieldTypes;
use Afsakar\FormMaker\Filament\Resources\FormBuilderResource\Pages;
use Afsakar\FormMaker\Models\FormBuilder;
use Afsakar\FormMaker\Models\Traits\HasOptions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;

class FormBuilderResource extends Resource
{
    use HasOptions;

    protected static ?string $model = FormBuilder::class;

    protected static ?string $slug = 'form-management/forms';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): string
    {
        return trans('filament-form-maker::form-maker.navigation_group');
    }

    public static function getModelLabel(): string
    {
        return trans('filament-form-maker::form-maker.resources.builder.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return trans('filament-form-maker::form-maker.resources.builder.plural_model_label');
    }

    public static function getNavigationIcon(): string | Htmlable | null
    {
        return config('filament-form-maker.navigation_icons.builder', 'tabler-forms');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Section::make(trans('filament-form-maker::form-maker.resources.builder.section_title'))
                    ->headerActions([
                        Forms\Components\Actions\Action::make('form_settings')
                            ->hiddenLabel()
                            ->slideOver()
                            ->tooltip(trans('filament-form-maker::form-maker.resources.builder.options.title'))
                            ->icon('heroicon-m-cog')
                            ->modalIcon('heroicon-m-cog')
                            ->modalHeading(trans('filament-form-maker::form-maker.resources.builder.options.title'))
                            ->modalDescription(trans('filament-form-maker::form-maker.resources.builder.options.description'))
                            ->fillForm(fn ($component) => $component->getState())
                            ->form([
                                static::staticFormBuilderOptions(),
                            ])
                            ->action(function (array $data, $component): void {
                                $state = $component->getState();
                                $state = array_merge($state, $data);
                                $component->state($state);
                            }),
                    ])
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(trans('filament-form-maker::form-maker.resources.builder.inputs.name'))
                                    ->required()
                                    ->columnSpanFull()
                                    ->live(true)
                                    ->unique('form_builders', 'name', ignoreRecord: true)
                                    ->afterStateUpdated(function ($state, $set) {
                                        $set('slug', str($state)->slug());
                                    }),
                                Forms\Components\Hidden::make('slug')
                                    ->hintIcon('heroicon-s-information-circle', 'Kısa Ad sadece alan oluşturulduğunda otomatik olarak oluşturulur. Kullanıcı için herhangi bir etkisi yoktur.')
                                    ->unique('form_builders', 'slug', ignoreRecord: true)
                                    ->dehydrateStateUsing(function ($state) {
                                        return Str::slug($state);
                                    })
                                    ->required(),
                                self::hiddenFormBuilderLabels(),
                            ]),
                    ]),
                Forms\Components\Repeater::make('sections')
                    ->label(trans('filament-form-maker::form-maker.resources.builder.sections.title'))
                    ->addActionLabel(trans('filament-form-maker::form-maker.resources.builder.sections.add_action_label'))
                    ->itemLabel(fn ($state) => $state['title'] ?? trans('filament-form-maker::form-maker.resources.builder.sections.new_section_label'))
                    ->collapsed(fn (string $operation) => $operation === 'edit')
                    ->cloneable()
                    ->relationship('sections')
                    ->reorderable()
                    ->orderColumn('order_column')
                    ->deleteAction(
                        fn ($action) => $action->requiresConfirmation(),
                    )
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label(trans('filament-form-maker::form-maker.resources.builder.sections.inputs.title'))
                                    ->lazy()
                                    ->nullable(),
                                Forms\Components\Select::make('columns')
                                    ->options(fn (): array => array_combine(range(1, 12), range(1, 12)))
                                    ->label(trans('filament-form-maker::form-maker.resources.builder.sections.inputs.columns'))
                                    ->required()
                                    ->searchable()
                                    ->live(onBlur: true)
                                    ->default(1)
                                    ->hint(trans('filament-form-maker::form-maker.resources.builder.sections.inputs.columns_hint')),
                            ]),

                        Forms\Components\Repeater::make('fields')
                            ->label(trans('filament-form-maker::form-maker.resources.builder.fields.title'))
                            ->addActionLabel(trans('filament-form-maker::form-maker.resources.builder.fields.add_action_label'))
                            ->minItems(1)
                            ->grid(3)
                            ->visible(fn ($get) => $get('columns'))
                            ->itemLabel(fn ($state) => $state['name'] ?? trans('filament-form-maker::form-maker.resources.builder.fields.new_field_label'))
                            ->collapsed(fn (string $operation) => $operation === 'edit')
                            ->cloneable()
                            ->deleteAction(
                                fn ($action) => $action->requiresConfirmation(),
                            )
                            ->extraItemActions([
                                Forms\Components\Actions\Action::make(trans('filament-form-maker::form-maker.resources.builder.fields.options.title'))
                                    ->slideOver()
                                    ->tooltip(trans('filament-form-maker::form-maker.resources.builder.fields.options.title'))
                                    ->icon('heroicon-m-cog')
                                    ->modalIcon('heroicon-m-cog')
                                    ->modalDescription(trans('filament-form-maker::form-maker.resources.builder.fields.options.description'))
                                    ->fillForm(fn (array $arguments, Forms\Components\Repeater $component) => $component->getItemState($arguments['item']))
                                    ->form(function ($get, array $arguments, Forms\Components\Repeater $component) {
                                        $arguments = $component->getState()[$arguments['item']];

                                        $type = $arguments['type'] ?? null;

                                        $columns = $get('columns');

                                        $dynamicOptions = match ($type) {
                                            'select' => static::selectFieldOptions(),
                                            'checkbox', 'radio' => static::checkboxRadioFieldOptions(),
                                            'text' => static::textFieldOptions(),
                                            'file' => static::getFileFieldOptions(),
                                            default => [],
                                        };

                                        $parentComponent = $component->getParentRepeater();

                                        $fields = $parentComponent?->getState();

                                        return [
                                            static::staticFieldOptions($columns),
                                            static::getConditionalFieldOptions($fields),
                                            ...$dynamicOptions,
                                        ];
                                    })
                                    ->action(function (array $data, array $arguments, Forms\Components\Repeater $component): void {
                                        $state = $component->getState();
                                        $state[$arguments['item']] = array_merge($state[$arguments['item']], $data);
                                        $component->state($state);
                                    }),
                            ])
                            ->cloneAction(fn ($action) => $action->action(function (array $arguments, Forms\Components\Repeater $component) {
                                $items = $component->getState();
                                $originalItem = $component->getState()[$arguments['item']];

                                $options = collect($originalItem['options'])->except(['htmlId', 'fieldId'])->toArray();

                                $clonedItem = array_merge($originalItem, [
                                    'name' => $originalItem['name'] . ' new',
                                    'options' => [
                                        'htmlId' => $originalItem['options']['htmlId'] . Str::random(2),
                                        'fieldId' => $originalItem['options']['fieldId'] . Str::random(2),
                                        ...$options,
                                    ],
                                ]);

                                $items[] = $clonedItem;
                                $component->state($items);

                                return $items;
                            }))
                            ->relationship('fields')
                            ->reorderable()
                            ->orderColumn('order_column')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(trans('filament-form-maker::form-maker.resources.builder.fields.inputs.name'))
                                    ->lazy()
                                    ->afterStateUpdated(function ($state, $set, $context) {
                                        if ($context === 'edit') {
                                            return;
                                        }
                                        $set('slug', str($state)->slug('_'));
                                    })
                                    ->required(),
                                Forms\Components\Select::make('type')
                                    ->label(trans('filament-form-maker::form-maker.resources.builder.fields.inputs.type'))
                                    ->options(FieldTypes::options())
                                    ->native(false)
                                    ->searchable()
                                    ->live()
                                    ->required(),
                                self::hiddenFieldLabels(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label(trans('filament-form-maker::form-maker.resources.builder.inputs.name')),
                Tables\Columns\TextColumn::make('sections_count')
                    ->label(trans('filament-form-maker::form-maker.resources.builder.sections.count'))
                    ->counts('sections'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ReplicateAction::make()
                    ->mutateRecordDataUsing(function (array $data): array {
                        $data['name'] .= ' Copy';
                        $data['slug'] .= '-copy';

                        return $data;
                    })
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->label(trans('filament-form-maker::form-maker.resources.builder.inputs.name'))
                            ->required()
                            ->live()
                            ->unique('form_builders', 'name', ignoreRecord: true)
                            ->afterStateUpdated(function ($state, $set) {
                                $set('slug', str($state)->slug());
                            }),
                        Forms\Components\Hidden::make('slug')
                            ->hintIcon('heroicon-s-information-circle', 'Kısa Ad sadece alan oluşturulduğunda otomatik olarak oluşturulur. Kullanıcı için herhangi bir etkisi yoktur.')
                            ->unique('form_builders', 'slug', ignoreRecord: true)
                            ->required(),
                    ])
                    ->after(function (FormBuilder $replica, $record): void {
                        $sections = $record->sections;

                        foreach ($sections as $section) {
                            $newSection = $section->replicate();
                            $newSection->form_builder_id = $replica->id;
                            $newSection->save();

                            foreach ($section->fields as $field) {
                                $newField = $field->replicate();
                                $newField->form_builder_section_id = $newSection->id;
                                $newField->save();
                                $newField->update([
                                    'options' => array_merge($field->options, [
                                        'htmlId' => $field->options['htmlId'] . Str::random(2),
                                        'fieldId' => $field->options['fieldId'] . Str::random(2),
                                    ]),
                                ]);
                            }
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFormBuilders::route('/'),
            'create' => Pages\CreateFormBuilder::route('/create'),
            'edit' => Pages\EditFormBuilder::route('/{record}/edit'),
        ];
    }
}
