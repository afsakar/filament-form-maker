<?php

namespace Afsakar\FormMaker\Filament\Resources\FormBuilderResource\Pages;

use Afsakar\FormMaker\Filament\Resources\FormBuilderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormBuilder extends EditRecord
{
    protected static string $resource = FormBuilderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return self::getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }
}
