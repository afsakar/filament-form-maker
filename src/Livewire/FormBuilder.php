<?php

namespace Afsakar\FormMaker\Livewire;

use Afsakar\FormMaker\Fields\FieldBuilder;
use Afsakar\FormMaker\Models\FormBuilder as FormBuilderModel;
use Afsakar\FormMaker\Models\Traits\SubmitAction;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class FormBuilder extends Component implements HasForms
{
    use InteractsWithForms;
    use SubmitAction;

    #[Locked]
    public ?int $formBuilderId;

    public ?array $fields = [];

    public FormBuilderModel $builderModel;

    public string $url = '';

    public ?array $options = null;

    public function mount()
    {
        $this->builderModel = FormBuilderModel::find($this->formBuilderId);

        $this->form->fill(); // @phpstan-ignore-line

        $this->url = url()->current();

        $this->options = data_get($this->builderModel, 'options');
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                FieldBuilder::make($this->builderModel),
            ])
            ->statePath('fields');
    }

    public function save()
    {
        $staticName = data_get($this->options, 'static_name') ?? $this->builderModel?->name; // @phpstan-ignore-line

        $data = [
            'fields' => $this->form->getState(), // @phpstan-ignore-line
            'form_name' => $staticName,
            'url' => $this->url,
            'admin_ids' => data_get($this->options, 'admin_ids') ?? [],
        ];

        $this->submit($data, $this->options);

        $this->form->fill(); // @phpstan-ignore-line
    }

    public function render(): View
    {
        return view('filament-form-maker::livewire.form-builder');
    }
}
