<?php

namespace Afsakar\FormMaker\Filament\Resources\FormBuilderCollectionResource\Pages;

use Afsakar\FormMaker\Filament\Resources\FormBuilderCollectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormBuilderCollections extends ListRecords
{
    protected static string $resource = FormBuilderCollectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
