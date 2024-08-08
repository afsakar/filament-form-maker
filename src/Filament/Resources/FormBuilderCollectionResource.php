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

class FormBuilderCollectionResource extends Resource
{
    protected static ?string $model = FormBuilderCollection::class;

    protected static ?string $navigationIcon = 'tabler-category';

    protected static ?string $slug = 'form-builder-collections';

    protected static ?string $navigationGroup = 'Form Yönetimi';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Form Koleksiyonu';

    protected static ?string $pluralModelLabel = 'Form Koleksiyonları';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Adı')
                            ->required(),
                        Forms\Components\Select::make('type')
                            ->label('Tipi')
                            ->live()
                            ->options([
                                'list' => 'Liste',
                                'model' => 'Model',
                            ])
                            ->default('list'),
                        Forms\Components\Select::make('model')
                            ->label('Model')
                            ->visible(fn ($get) => $get('type') === 'model')
                            ->live()
                            ->options(function () {
                                $collections = FormBuilderHelper::getAllResources();

                                return [
                                    config('filament-form-maker.extra_collections'),
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
                                Forms\Components\TextInput::make('value')
                                    ->label('Değer')
                                    ->required(),
                                Forms\Components\TextInput::make('label')
                                    ->label('Etiket')
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
