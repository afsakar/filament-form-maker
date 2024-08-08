<?php

namespace Afsakar\FormMaker;

use Afsakar\FormMaker\Filament\Resources\FormBuilderCollectionResource;
use Afsakar\FormMaker\Filament\Resources\FormBuilderDataResource;
use Afsakar\FormMaker\Filament\Resources\FormBuilderResource;
use Filament\Contracts\Plugin;
use Filament\Panel;

class FormMakerPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-form-maker';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                FormBuilderResource::class,
                FormBuilderCollectionResource::class,
                FormBuilderDataResource::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
