<?php

namespace Waynelogic\MagicForms\Filament\Resources\FormRecords\Pages;

use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Resources\Pages\ListRecords;
use Waynelogic\MagicForms\Filament\Exports\FormRecordExporter;
use Waynelogic\MagicForms\Filament\Resources\FormRecords\FormRecordResource;

class ListFormRecords extends ListRecords
{
    protected static string $resource = FormRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->exporter(FormRecordExporter::class)
                ->columnMappingColumns(3)
                ->formats([
                    ExportFormat::Csv,
                ]),
        ];
    }
}
