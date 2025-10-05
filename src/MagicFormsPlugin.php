<?php

namespace Waynelogic\MagicForms;

use Filament\Contracts\Plugin;
use Filament\Panel;

use Waynelogic\MagicForms\Filament\Resources\FormRecords\FormRecordResource;
use Waynelogic\MagicForms\Models\FormRecord;

class MagicFormsPlugin implements Plugin
{

    public function getId(): string
    {
        return 'magic-forms';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            FormRecordResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }
}
