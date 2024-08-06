<?php

namespace Afsakar\FormMaker\Commands;

use Illuminate\Console\Command;

class FormMakerCommand extends Command
{
    public $signature = 'filament-form-maker';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
