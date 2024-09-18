<?php

namespace Afsakar\FormMaker\Filament\Resources;

use Afsakar\FormMaker\Filament\Resources\FormBuilderCollectionResource\Pages;
use Afsakar\FormMaker\Helpers\FormBuilderHelper;
use Afsakar\FormMaker\Models\FormBuilderCollection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class FormBuilderCollectionResource extends Resource
{
    protected static ?string $model = FormBuilderCollection::class;

    protected static ?string $slug = 'form-management/collections';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): string
    {
        return trans('filament-form-maker::form-maker.navigation_group');
    }

    public static function getModelLabel(): string
    {
        return trans('filament-form-maker::form-maker.resources.collections.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return trans('filament-form-maker::form-maker.resources.collections.plural_model_label');
    }

    public static function getNavigationIcon(): string | Htmlable | null
    {
        return config('filament-form-maker.navigation_icons.collections', 'tabler-forms');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Adı')
                            ->required(),
                        Forms\Components\ToggleButtons::make('type')
                            ->label('Tipi')
                            ->live()
                            ->inline()
                            ->options([
                                'list' => 'Liste',
                                'model' => 'Model',
                            ])
                            ->default('list')
                            ->required(),
                        Forms\Components\Select::make('model')
                            ->label('Model')
                            ->visible(fn ($get) => $get('type') === 'model')
                            ->native(false)
                            ->preload()
                            ->options(function () {
                                $collections = FormBuilderHelper::getAllResources();

                                return [
                                    ...(array) config('filament-form-maker.extra_collections'),
                                    ...$collections,
                                ];
                            })
                            ->required(),
                        Forms\Components\Repeater::make('values')
                            ->label('Değerler')
                            ->visible(fn ($get) => $get('type') === 'list')
                            ->addActionLabel('Değer Ekle')
                            ->grid(3)
                            ->schema([
                                Forms\Components\TextInput::make('label')
                                    ->label('Etiket')
                                    ->required(),
                                Forms\Components\TextInput::make('value')
                                    ->label('Değer')
                                    ->required(),
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
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipi')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(function ($state, $record) {
                        return match ($state) {
                            default => 'Liste',
                            'model' => (new $record->model)->getClassName(),
                        };
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListFormBuilderCollections::route('/'),
            'create' => Pages\CreateFormBuilderCollection::route('/create'),
            'edit' => Pages\EditFormBuilderCollection::route('/{record}/edit'),
        ];
    }
}
