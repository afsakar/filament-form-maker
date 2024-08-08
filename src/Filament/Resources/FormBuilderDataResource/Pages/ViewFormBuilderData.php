<?php

namespace Afsakar\FormMaker\Filament\Resources\FormBuilderDataResource\Pages;

use Afsakar\FormMaker\Filament\Resources\FormBuilderDataResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFormBuilderData extends ViewRecord
{
    protected static string $resource = FormBuilderDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
