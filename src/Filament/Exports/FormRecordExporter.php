<?php

namespace Waynelogic\MagicForms\Filament\Exports;

use Filament\Actions\Exports\ExportColumn;
use Waynelogic\MagicForms\Models\FormRecord;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class FormRecordExporter extends Exporter
{
    protected static ?string $model = FormRecord::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('manager.name')
                ->label('Менеджер'),
            ExportColumn::make('group')
                ->label('Группа'),
            ExportColumn::make('form_data')
                ->label('Данные формы'),
            ExportColumn::make('ip')
                ->label('IP'),
            ExportColumn::make('country')
                ->label('Страна'),
            ExportColumn::make('city')
                ->label('Город'),
            ExportColumn::make('created_at')
                ->label('Дата создания'),
            ExportColumn::make('updated_at')
                ->label('Дата обновления'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your form record export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
