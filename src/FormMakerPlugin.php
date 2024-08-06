<?php

namespace Afsakar\FormMaker;

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
        //
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
