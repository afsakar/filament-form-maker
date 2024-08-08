<?php

namespace Afsakar\FormMaker\Filament\Forms;

use Afsakar\FormMaker\Models\FormBuilder;
use Filament\Forms;
use Filament\Forms\Components\Concerns\EntanglesStateWithSingularRelationship;

class FormPicker extends Forms\Components\Group
{
    use EntanglesStateWithSingularRelationship;
    use Forms\Components\Concerns\CanBeCollapsed;
    use Forms\Components\Concerns\CanBeValidated;

    protected function setUp(): void
    {
        $this->schema([
            Forms\Components\Select::make('form_builder_id')
                ->label('Form Seç')
                ->native(false)
                ->searchable()
                ->preload()
                ->options(fn () => FormBuilder::pluck('name', 'id')->toArray())
                ->required(),
        ]);
    }
}
