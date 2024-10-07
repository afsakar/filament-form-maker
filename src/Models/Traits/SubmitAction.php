<?php

namespace Afsakar\FormMaker\Models\Traits;

use Afsakar\FormMaker\Filament\Resources\FormBuilderDataResource;
use Afsakar\FormMaker\Models\FormBuilderData;
use Afsakar\FormMaker\Models\FormBuilderField;
use Afsakar\FormMaker\Notifications\MessageNotification;
use Filament\Facades\Filament;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

trait SubmitAction
{
    public function submit($data, $options = []): void
    {
        $fields = self::manipulateFields($data['fields']);

        $form_name = $data['form_name'];

        $expects = [];

        collect($fields)->map(function ($field, $key) use (&$expects) {
            if ($field instanceof TemporaryUploadedFile) {
                $expects[] = $key;
            }
        });

        $form = FormBuilderData::create(
            [
                'name' => $form_name,
                'url' => $data['url'] ?? request()->url(),
                'locale' => app()->getLocale(),
                'fields' => collect($fields)->except($expects)->toArray(),
            ]
        );

        collect($fields)->map(function ($field) use ($form) {
            if ($field instanceof TemporaryUploadedFile) {
                $this->attachFile($field, $form);
            }
        });

        $lines = collect($fields)->except($expects)->map(function ($value, $key) {
            if (is_array($value)) {
                $value = implode(', ', $value);
            }

            return "{$key}: {$value}";
        })->toArray();

        $messageNotification = MessageNotification::make(
            (new MailMessage)
                ->subject(trans('filament-form-maker::form-maker.notification.mail.title', ['form_name' => $form_name]))
                ->greeting(trans('filament-form-maker::form-maker.notification.mail.greeting'))
                ->lines($lines)
                ->action(trans('filament-form-maker::form-maker.notification.mail.view'), FormBuilderDataResource::getUrl('view', ['record' => $form])),
            Notification::make()
                ->title(trans('filament-form-maker::form-maker.notification.mail.title', ['form_name' => $form_name]))
                ->actions([
                    Action::make('view')
                        ->label(trans('filament-form-maker::form-maker.notification.mail.view'))
                        ->url(FormBuilderDataResource::getUrl('view', ['record' => $form])),
                ])
        );

        $userModel = Filament::auth()->getProvider()->getModel(); // @phpstan-ignore-line

        $userModel::notification($messageNotification, data_get($data, 'admin_ids', []));

        Notification::make()
            ->title(data_get($options, 'notifications.title') ?? trans('filament-form-maker::form-maker.notification.toast.title'))
            ->body(data_get($options, 'notifications.body') ?? trans('filament-form-maker::form-maker.notification.toast.body'))
            ->send();
    }

    protected function attachFile($file, $form, $collection = 'form-builder'): void
    {
        $file = is_array($file) ? head($file) : $file;

        if ($file instanceof TemporaryUploadedFile) {
            $form->addMedia($file)->toMediaCollection($collection);
        }
    }

    protected static function manipulateFields($data): array
    {
        $keys = collect($data)->keys();

        $fields = FormBuilderField::whereIn('options->fieldId', $keys)->get();

        $fields = $fields->mapWithKeys(function ($field) {
            $staticName = data_get($field->options, 'static_name') ?? $field->name; // @phpstan-ignore-line

            return [$field->options['fieldId'] => $staticName]; // @phpstan-ignore-line
        });

        return collect($data)->mapWithKeys(function ($value, $key) use ($fields) {
            return [
                $fields[$key] => $value,
            ];
        })->toArray();
    }
}
