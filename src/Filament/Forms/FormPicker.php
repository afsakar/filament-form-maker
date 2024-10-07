<?php

namespace Afsakar\FormMaker\Filament\Forms;

use Afsakar\FormMaker\Models\FormBuilder;
use Filament\Forms;

class FormPicker extends Forms\Components\Select
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->searchable();
        $this->preload();
        $this->options(fn () => FormBuilder::pluck('name', 'id')->toArray());
        $this->required();
    }
}
