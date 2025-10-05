<?php

namespace Waynelogic\MagicForms\Filament\Resources\FormRecords;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Waynelogic\MagicForms\Filament\Resources\FormRecords\Pages\CreateFormRecord;
use Waynelogic\MagicForms\Filament\Resources\FormRecords\Pages\EditFormRecord;
use Waynelogic\MagicForms\Filament\Resources\FormRecords\Pages\ListFormRecords;
use Waynelogic\MagicForms\Filament\Resources\FormRecords\Pages\ViewFormRecord;
use Waynelogic\MagicForms\Filament\Resources\FormRecords\Schemas\FormRecordForm;
use Waynelogic\MagicForms\Filament\Resources\FormRecords\Schemas\FormRecordInfolist;
use Waynelogic\MagicForms\Filament\Resources\FormRecords\Tables\FormRecordsTable;
use Waynelogic\MagicForms\Models\FormRecord;

class FormRecordResource extends Resource
{
    protected static ?string $model = FormRecord::class;

    protected static ?string $navigationLabel = 'Magic Forms';

    protected static ?string $label = 'Запись';

    protected static ?string $pluralLabel = 'Записи';

    protected static ?string $recordTitleAttribute = 'id';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBolt;

    protected static string | BackedEnum | null $activeNavigationIcon = Heroicon::Bolt;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereNull('read_at')->count();
    }

    public static function infolist(Schema $schema): Schema
    {
        return FormRecordInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FormRecordsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFormRecords::route('/'),
            'view' => ViewFormRecord::route('/{record}'),
        ];
    }
}
