<?php

namespace Afsakar\FormMaker\Fields;

use Afsakar\FormMaker\Fields\Classes\Checkbox;
use Afsakar\FormMaker\Fields\Classes\DatePicker;
use Afsakar\FormMaker\Fields\Classes\FileInput;
use Afsakar\FormMaker\Fields\Classes\PhoneInput;
use Afsakar\FormMaker\Fields\Classes\Radio;
use Afsakar\FormMaker\Fields\Classes\Select;
use Afsakar\FormMaker\Fields\Classes\Textarea;
use Afsakar\FormMaker\Fields\Classes\TextInput;
use Afsakar\FormMaker\Models\FormBuilder;
use Filament\Forms;
use Illuminate\Support\HtmlString;

final class FieldBuilder
{
    public function __construct(public FormBuilder $builder) {}

    public static function make(FormBuilder $builder): Forms\Components\Group
    {
        app(self::class, ['builder' => $builder]);

        return self::makeSections($builder);
    }

    protected static function makeSections($builder): Forms\Components\Group
    {
        $sections = $builder->sections;

        $newSections = [];
        $fields = [];

        foreach ($sections as $section) {

            foreach ($section->fields as $field) {
                $fields[$section->id][] = self::getField($field);
            }

            $newSections[] =
                Forms\Components\Grid::make()
                    ->columns([
                        'xs' => 1,
                        'sm' => 1,
                        'md' => $section->columns ?? 1,
                        'lg' => $section->columns ?? 1,
                        'xl' => $section->columns ?? 1,
                        'default' => 1,
                    ])
                    ->schema([
                        self::placeholder($section->title),
                        ...$fields[$section->id],
                    ]);
        }

        return Forms\Components\Group::make()
            ->schema($newSections);
    }

    protected static function placeholder(?string $title): Forms\Components\Placeholder
    {
        return Forms\Components\Placeholder::make('title')
            ->label(new HtmlString('<div class="afsakar-section-title flex items-center relative gap-[50px] sm:gap-20">
                        <p class="text text-[20px] md:text-[18px] sm:text-[16px] leading-tight font-bold w-fit relative z-2">' . $title . '</p>
                      </div>'))
            ->columnSpanFull()
            ->hidden(! $title)
            ->dehydrated(false);
    }

    // TODO: Add extra fields feature
    protected static function getExtraFields($field): array
    {
        $extraFields = config('filament-form-maker.extra_fields');

        $fields = [];

        foreach ($extraFields as $key => $extraField) {
            if ($field->type->value === $key) {
                $fields[] = $extraField::make($field);
            }
        }

        return $fields;
    }

    protected static function getField($field): Forms\Components\Field
    {
        return match ($field->type->value) {
            'text' => TextInput::make($field),
            'phone' => PhoneInput::make($field),
            'select' => Select::make($field),
            'textarea' => Textarea::make($field),
            'file' => FileInput::make($field),
            'date' => DatePicker::make($field),
            'checkbox' => Checkbox::make($field),
            'radio' => Radio::make($field),
            default => Forms\Components\Hidden::make('null')->dehydrated(false),
        };
    }
}
