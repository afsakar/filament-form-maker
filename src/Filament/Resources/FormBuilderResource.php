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
use Illuminate\Support\Str;

class FormBuilderResource extends Resource
{
    use HasOptions;

    protected static ?string $model = FormBuilder::class;

    protected static ?string $navigationIcon = 'tabler-forms';

    protected static ?string $slug = 'form-builder';

    protected static ?string $navigationGroup = 'Form Yönetimi';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Form Oluşturucu';

    protected static ?string $pluralModelLabel = 'Form Oluşturucular';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Section::make('Form Bilgileri')
                    ->headerActions([
                        Forms\Components\Actions\Action::make('Form Ayarları')
                            ->slideOver()
                            ->tooltip('Form Ayarları')
                            ->icon('heroicon-m-cog')
                            ->modalIcon('heroicon-m-cog')
                            ->modalDescription('Daha Fazla Form Ayarları')
                            ->fillForm(fn (
                                $state,
                                array $arguments,
                                $component
                            ) => $component->getState())
                            ->form(function ($get, array $arguments, $component, $state) {
                                $arguments = $component->getState();

                                return [
                                    static::staticFormBuilderOptions(),
                                ];
                            })
                            ->action(function (array $data, array $arguments, $component): void {
                                $state = $component->getState();
                                $state = array_merge($state, $data);
                                $component->state($state);
                            }),
                    ])
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Adı')
                                    ->required()
                                    ->live(true)
                                    ->unique('form_builders', 'name', ignoreRecord: true)
                                    ->afterStateUpdated(function ($state, $set) {
                                        $set('slug', str($state)->slug());
                                    }),
                                Forms\Components\TextInput::make('slug')
                                    ->label('Kısa Adı')
                                    ->hintIcon('heroicon-s-information-circle', 'Kısa Ad sadece alan oluşturulduğunda otomatik olarak oluşturulur. Kullanıcı için herhangi bir etkisi yoktur.')
                                    ->unique('form_builders', 'slug', ignoreRecord: true)
                                    ->dehydrateStateUsing(function ($state) {
                                        $slug = str($state)->slug();
                                        $existingSlugs = FormBuilder::where('slug', 'LIKE', "{$slug}%")
                                            ->pluck('slug');

                                        if ($existingSlugs->contains($slug)) {
                                            $numbers = $existingSlugs->map(function ($existingSlug) use ($slug) {
                                                if (preg_match('/' . preg_quote($slug, '/') . '-(\d+)$/', $existingSlug, $matches)) {
                                                    return intval($matches[1]);
                                                }

                                                return null;
                                            })->filter()->sort()->values();

                                            if ($numbers->isNotEmpty()) {
                                                $newNumber = $numbers->last() + 1;
                                            } else {
                                                $newNumber = 2;
                                            }

                                            return $slug . '-' . $newNumber;
                                        }

                                        return $slug;
                                    })
                                    ->required(),
                                self::hiddenFormBuilderLabels(),
                            ]),
                    ]),
                Forms\Components\Repeater::make('sections')
                    ->label('Bölümler')
                    ->addActionLabel('Bölüm Ekle')
                    ->itemLabel(fn ($state) => $state['title'] ?? 'Yeni Bölüm')
                    ->collapsed(fn (string $operation) => $operation === 'edit')
                    ->cloneable()
                    ->relationship('sections')
                    ->reorderable()
                    ->orderColumn('order_column')
                    ->deleteAction(
                        fn ($action) => $action->requiresConfirmation(),
                    )
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Başlık')
                            ->lazy()
                            ->nullable(),
                        Forms\Components\Select::make('columns')
                            ->options(fn (): array => array_combine(range(1, 3), range(1, 3)))
                            ->label('Sütun Sayısı')
                            ->required()
                            ->default(1)
                            ->hint('Bölümün sütun sayısını belirler.'),

                        Forms\Components\Repeater::make('fields')
                            ->label('Alanlar')
                            ->addActionLabel('Alan Ekle')
                            ->minItems(1)
                            ->grid(3)
                            ->itemLabel(fn ($state) => $state['name'] ?? 'Yeni Alan')
                            ->collapsed(fn (string $operation) => $operation === 'edit')
                            ->cloneable()
                            ->deleteAction(
                                fn ($action) => $action->requiresConfirmation(),
                            )
                            ->extraItemActions([
                                Forms\Components\Actions\Action::make('Alan Ayarları')
                                    ->slideOver()
                                    ->tooltip('Alan Ayarları')
                                    ->icon('heroicon-m-cog')
                                    ->modalIcon('heroicon-m-cog')
                                    ->modalDescription('Daha Fazla Alan Ayarları')
                                    ->fillForm(fn (
                                        $state,
                                        array $arguments,
                                        Forms\Components\Repeater $component
                                    ) => $component->getItemState($arguments['item']))
                                    ->form(function ($get, array $arguments, Forms\Components\Repeater $component, $state) {
                                        $arguments = $component->getState()[$arguments['item']];

                                        $type = $arguments['type'] ?? null;

                                        $collections = match ($type) {
                                            'select' => static::selectFieldOptions(),
                                            'checkbox', 'radio' => static::checkboxRadioFieldOptions(),
                                            'text' => static::textFieldOptions(),
                                            'file' => static::getFileFieldOptions(),
                                            default => [],
                                        };

                                        $parentComponent = $component->getParentRepeater();

                                        $fields = $parentComponent?->getState();

                                        return [
                                            static::staticFieldOptions(),
                                            static::getConditionalFieldOptions($fields),
                                            ...$collections,
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
                                    ->label('Alan Adı')
                                    ->lazy()
                                    ->afterStateUpdated(function ($state, $set, $context) {
                                        if ($context === 'edit') {
                                            return;
                                        }
                                        $set('slug', str($state)->slug('_'));
                                    })
                                    ->required(),
                                Forms\Components\Select::make('type')
                                    ->label('Alan Tipi')
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
                    ->label('Adı'),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Kısa Adı'),
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
                            ->label('Adı')
                            ->required()
                            ->live(true)
                            ->unique('form_builders', 'name', ignoreRecord: true)
                            ->afterStateUpdated(function ($state, $set) {
                                $set('slug', str($state)->slug());
                            }),
                        Forms\Components\TextInput::make('slug')
                            ->label('Kısa Adı')
                            ->readOnly()
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

                            // Her section'ın fields ilişkisini de kopyala
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
