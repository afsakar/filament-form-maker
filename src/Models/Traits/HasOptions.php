<?php

namespace Afsakar\FormMaker\Models\Traits;

use Afsakar\FormMaker\Models\FormBuilderCollection;
use Filament\Facades\Filament;
use Filament\Forms;

trait HasOptions
{
    use HasHiddenOptions;

    protected static function staticFieldOptions(?int $columns): Forms\Components\Grid
    {
        return Forms\Components\Grid::make()
            ->statePath('options')
            ->columns(2)
            ->schema(function () use ($columns) {
                $colspanOptions = [];

                foreach (range(1, $columns) as $column) {
                    $colspanOptions[$column] = $column . '/' . $columns;
                }

                $colspanOptions['full'] = 'Tam Genişlik';

                return [
                    Forms\Components\TextInput::make('static_name')
                        ->label('Bildirim Adı')
                        ->nullable(),
                    Forms\Components\Toggle::make('is_required')
                        ->label('Zorunlu Alan')
                        ->default(true),
                    Forms\Components\TextInput::make('fieldId')
                        ->required()
                        ->default(str()->random(6))
                        ->readOnly()
                        ->label(__('Alan ID')),
                    Forms\Components\TextInput::make('htmlId')
                        ->required()
                        ->default(str()->random(6))
                        ->readOnly()
                        ->label(__('HTML ID')),
                    Forms\Components\Select::make('column_span')
                        ->label('Sütun Genişliği')
                        ->options($colspanOptions)
                        ->searchable()
                        ->default('1'),
                ];
            })
            ->columns(1);
    }

    protected static function staticFormBuilderOptions(): Forms\Components\Group
    {
        return Forms\Components\Group::make()
            ->statePath('options')
            ->schema([
                Forms\Components\TextInput::make('static_name')
                    ->label('Bildirim Adı')
                    ->nullable(),
                Forms\Components\ColorPicker::make('background_color')
                    ->label('Arka Plan Rengi')
                    ->default('transparent'),
                Forms\Components\Section::make('Mail Bildirimleri')
                    ->schema([
                        Forms\Components\Select::make('admin_ids')
                            ->label('Kullanıcı E-postaları')
                            ->hintIcon('tabler-info-circle', 'Forum\'un doldurulduktan sonra iletilmesini istediğiniz kullanıcıları seçiniz.')
                            ->native(false)
                            ->searchable()
                            ->options(function () {
                                $userModel = Filament::auth()->getProvider()->getModel(); // @phpstan-ignore-line

                                return $userModel::all()->pluck('name', 'id')->toArray();
                            })
                            ->multiple()
                            ->nullable(),
                    ]),
            ]);
    }

    protected static function selectFieldOptions(): array
    {
        return [
            Forms\Components\Section::make('Seçim Kutusu Ayarları')
                ->collapsed()
                ->statePath('options')
                ->schema([
                    Forms\Components\Toggle::make('is_multiple')
                        ->label('Çoklu Seçim')
                        ->default(false),
                    Forms\Components\Toggle::make('is_searchable')
                        ->label('Arama Yapılabilir')
                        ->default(false),
                    Forms\Components\Select::make('data_source')
                        ->label('Veri Kaynağı')
                        ->native(false)
                        ->searchable()
                        ->preload()
                        ->options(FormBuilderCollection::pluck('name', 'id')->toArray())
                        ->required(),
                ]),
        ];
    }

    protected static function checkboxRadioFieldOptions(): array
    {
        return [
            Forms\Components\Section::make('Onay Kutusu / Seçim Düğmesi Ayarları')
                ->collapsed()
                ->statePath('options')
                ->schema([
                    Forms\Components\Select::make('data_source')
                        ->label('Veri Kaynağı')
                        ->native(false)
                        ->searchable()
                        ->preload()
                        ->options(FormBuilderCollection::pluck('name', 'id')->toArray())
                        ->required(),
                    Forms\Components\ToggleButtons::make('columns')
                        ->label('Sütun Sayısı')
                        ->inline()
                        ->options([
                            1 => 'Tek Sütun',
                            2 => 'İki Sütun',
                            3 => 'Üç Sütun',
                            4 => 'Dört Sütun',
                        ])
                        ->default(2),
                ]),
        ];
    }

    protected static function textFieldOptions(): array
    {
        return [
            Forms\Components\Section::make('Metin Alanı Ayarları')
                ->collapsed()
                ->statePath('options')
                ->schema([
                    Forms\Components\Select::make('field_type')
                        ->label('Metin Alanı Tipi')
                        ->native(false)
                        ->searchable()
                        ->preload()
                        ->live()
                        ->options([
                            'text' => 'Metin',
                            'email' => 'E-posta',
                            'url' => 'URL',
                            'number' => 'Sayı',
                        ])
                        ->default('text')
                        ->required(),
                    Forms\Components\Grid::make()
                        ->visible(fn ($get) => $get('field_type') === 'number')
                        ->schema([
                            Forms\Components\TextInput::make('max_value')
                                ->label('Maksimum Değer')
                                ->type('number')
                                ->nullable(),
                            Forms\Components\TextInput::make('min_value')
                                ->label('Minimum Değer')
                                ->type('number')
                                ->nullable(),
                        ]),
                ]),
        ];
    }

    protected static function getFileFieldOptions(): array
    {
        return [
            Forms\Components\Section::make('Dosya Alanı Ayarları')
                ->statePath('options')
                ->collapsed()
                ->schema([
                    Forms\Components\TextInput::make('max_size')
                        ->label('Maksimum Dosya Boyutu')
                        ->type('number')
                        ->default(5120)
                        ->hint('KB cinsinden')
                        ->suffix('KB')
                        ->nullable(),
                    Forms\Components\Select::make('accepted_file_types')
                        ->label('Kabul Edilen Dosya Türleri')
                        ->options([
                            'application/pdf' => 'PDF',
                            'application/msword' => 'Word',
                            'application/vnd.ms-excel' => 'Excel',
                            'application/vnd.ms-powerpoint' => 'PowerPoint',
                            'application/zip' => 'Zip',
                            'image/*' => 'Resim',
                        ])
                        ->native(false)
                        ->searchable()
                        ->multiple()
                        ->required(),
                    Forms\Components\TextInput::make('helper_text')
                        ->label('Yardımcı Metin')
                        ->nullable(),
                ]),
        ];
    }

    protected static function getConditionalFieldOptions($getFields): Forms\Components\Component
    {
        if (filled($getFields)) {
            $getFields = collect($getFields)
                ->pluck('fields')
                ->mapWithKeys(function (array $item) {
                    return $item;
                });
        }

        return Forms\Components\Section::make('Koşullu Görünürlük')
            ->statePath('options.visibility')
            ->collapsed()
            ->schema([
                Forms\Components\Toggle::make('active')
                    ->live()
                    ->label('Koşullu Görünürlük Aktif'),

                Forms\Components\Select::make('fieldId')
                    ->label('Koşul Bağlanacak Alan:')
                    ->live()
                    ->searchable(false)
                    ->visible(fn ($get): bool => ! empty($get('active')))
                    ->required(fn ($get): bool => ! empty($get('active')))
                    ->native(false)
                    ->searchable()
                    ->options(optional($getFields)->where('type', 'select')->pluck('name', 'options.fieldId')->toArray())
                    ->afterStateUpdated(fn ($get, $set) => $set('values', null)),

                Forms\Components\Select::make('values')
                    ->label('Görünürlük Değeri:')
                    ->live()
                    ->searchable(false)
                    ->visible(fn ($get): bool => ! empty($get('active')))
                    ->helperText('Eğer herhangi bir değer seçildiği takdirde görünür olmasını istiyorsanız bu alanı boş bırakın.')
                    ->native(false)
                    ->searchable()
                    ->options(function ($get) use ($getFields) {
                        $getRelated = $getFields->where('options.fieldId', $get('fieldId'))->where('type', 'select')->first();

                        if ($get('fieldId') === null) {
                            return [];
                        }

                        if (! isset($getRelated['options']['data_source'])) {
                            return [];
                        }

                        $collection = FormBuilderCollection::find($getRelated['options']['data_source']);

                        $options = [];

                        if ($collection?->type === 'list') { // @phpstan-ignore-line
                            collect($collection->values)->each(function ($value) use (&$options) { // @phpstan-ignore-line
                                $options[$value['value']] = $value['label'];
                            });
                        } else {
                            $model = $collection?->model; // @phpstan-ignore-line
                            $label = $model::getLabelColumn();

                            $options = $model::all()->pluck($label, $label)->toArray();
                        }

                        return $options;
                    }),
            ]);
    }
}
