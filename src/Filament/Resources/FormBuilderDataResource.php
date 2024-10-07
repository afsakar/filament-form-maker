<?php

namespace Afsakar\FormMaker\Filament\Resources;

use Afsakar\FormMaker\Enums\FormStatus;
use Afsakar\FormMaker\Filament\Resources\FormBuilderDataResource\Pages;
use Afsakar\FormMaker\Models\FormBuilderData;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class FormBuilderDataResource extends Resource
{
    protected static ?string $model = FormBuilderData::class;

    protected static ?string $slug = 'form-management/data';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): string
    {
        return trans('filament-form-maker::form-maker.navigation_group');
    }

    public static function getModelLabel(): string
    {
        return trans('filament-form-maker::form-maker.resources.form_data.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return trans('filament-form-maker::form-maker.resources.form_data.plural_model_label');
    }

    public static function getNavigationIcon(): string | Htmlable | null
    {
        return config('filament-form-maker.navigation_icons.form_data', 'tabler-forms');
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getEloquentQuery()->where('status', 'open')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->schema([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')
                            ->label(trans('filament-form-maker::form-maker.resources.form_data.inputs.name')),

                        TextEntry::make('status')
                            ->label(trans('filament-form-maker::form-maker.resources.form_data.inputs.status'))
                            ->badge()
                            ->formatStateUsing(fn ($state) => $state?->label())
                            ->color(fn ($state) => $state?->color()),

                        TextEntry::make('ip')
                            ->label(trans('filament-form-maker::form-maker.resources.form_data.inputs.ip_address')),

                        TextEntry::make('url')
                            ->label('URL'),

                        TextEntry::make('user_agent')
                            ->columnSpanFull()
                            ->label(trans('filament-form-maker::form-maker.resources.form_data.inputs.user_agent')),

                        TextEntry::make('file')
                            ->label(trans('filament-form-maker::form-maker.resources.form_data.inputs.file.label'))
                            ->columnSpanFull()
                            ->visible(fn ($record) => $record->getFirstMedia('file'))
                            ->default(function ($record) {
                                $cv = $record->getFirstMedia('file') ? $record->getFirstMediaUrl('file') : null;

                                return new HtmlString("<a href='{$cv}' download target='_self'>" . trans('filament-form-maker::form-maker.resources.form_data.inputs.file.download') . '</a>');
                            }),
                    ]),

                Tabs::make()
                    ->schema([
                        Tabs\Tab::make(trans('filament-form-maker::form-maker.resources.form_data.section_title'))
                            ->columns(2)
                            ->statePath('fields')
                            ->schema(
                                fn ($state) => collect($state)
                                    ->reject(fn ($value, $key) => strtolower((string) $key) === 'locale')
                                    ->sortBy(fn ($value, $key) => $key)
                                    ->filter()
                                    ->map(fn ($value, $key) => TextEntry::make($key)
                                        ->label($key)
                                        ->getStateUsing(fn () => $value))->values()->all()
                            ),
                    ]),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->badge()
                    ->label(trans('filament-form-maker::form-maker.resources.form_data.inputs.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state?->label())
                    ->color(fn ($state) => $state?->color())
                    ->label(trans('filament-form-maker::form-maker.resources.form_data.inputs.status'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label(trans('filament-form-maker::form-maker.resources.form_data.inputs.created_at'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->label(trans('filament-form-maker::form-maker.resources.form_data.inputs.updated_at'))
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('close')
                    ->label(trans('filament-form-maker::form-maker.resources.form_data.actions.close.label'))
                    ->color('success')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->modalHeading(trans('filament-form-maker::form-maker.resources.form_data.actions.close.modal.title'))
                    ->modalDescription(trans('filament-form-maker::form-maker.resources.form_data.actions.close.modal.body'))
                    ->successNotificationTitle(trans('filament-form-maker::form-maker.resources.form_data.actions.close.modal.success'))
                    ->action(function ($record, $action) {
                        $record->update(['status' => 'closed']);

                        return $action->success();
                    })->visible(fn ($record) => $record->status === FormStatus::OPEN),
                Tables\Actions\Action::make('open')
                    ->color('danger')
                    ->label(trans('filament-form-maker::form-maker.resources.form_data.actions.open.label'))
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->modalHeading(trans('filament-form-maker::form-maker.resources.form_data.actions.open.modal.title'))
                    ->modalDescription(trans('filament-form-maker::form-maker.resources.form_data.actions.open.modal.body'))
                    ->successNotificationTitle(trans('filament-form-maker::form-maker.resources.form_data.actions.open.modal.success'))
                    ->action(function ($record, $action) {
                        $record->update(['status' => 'open']);

                        return $action->success();
                    })->visible(fn ($record) => $record->status === FormStatus::CLOSED),
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
            'index' => Pages\ListFormBuilderData::route('/'),
            'create' => Pages\CreateFormBuilderData::route('/create'),
            'view' => Pages\ViewFormBuilderData::route('/{record}'),
            'edit' => Pages\EditFormBuilderData::route('/{record}/edit'),
        ];
    }
}
