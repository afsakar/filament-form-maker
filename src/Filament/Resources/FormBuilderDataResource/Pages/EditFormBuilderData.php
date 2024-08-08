<?php

namespace Afsakar\FormMaker\Filament\Resources\FormBuilderDataResource\Pages;

use Afsakar\FormMaker\Filament\Resources\FormBuilderDataResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormBuilderData extends EditRecord
{
    protected static string $resource = FormBuilderDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
