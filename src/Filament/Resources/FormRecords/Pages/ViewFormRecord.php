<?php

namespace Waynelogic\MagicForms\Filament\Resources\FormRecords\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Waynelogic\MagicForms\Filament\Resources\FormRecords\FormRecordResource;

class ViewFormRecord extends ViewRecord
{
    protected static string $resource = FormRecordResource::class;

    public function mount(int | string $record): void
    {
        parent::mount($record);

        $this->record->markAsRead();
    }
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
