<?php

namespace Waynelogic\MagicForms\Filament\Resources\FormRecords\Schemas;

use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Waynelogic\MagicForms\Models\FormRecord;

class FormRecordInfolist
{
    /**
     * @throws Exception
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('manager.name')
                    ->label('Менеджер')
                    ->default('Не назначен')
                    ->action(
                        Action::make('set_manger')
                            ->label('Назначить менеджера')
                            ->schema([
                                Select::make('manager_id')
                                    ->label('Менеджер')
                                    ->relationship('manager', 'name')
                                    ->required(),
                            ])
                            ->action(function (FormRecord $record, array $data): void {
                                $record->manager_id = $data['manager_id'];
                                $record->save();
                            })
                    ),

                TextEntry::make('group')
                    ->label('Группа'),

                KeyValueEntry::make('form_data')
                    ->label('Данные формы')
                    ->keyLabel('Реквизит')
                    ->valueLabel('Значение')
                ->columnSpanFull(),

                Section::make('Дополнительная информация')->schema([
                    TextEntry::make('city')
                        ->label('Город')
                        ->icon('heroicon-m-home'),
                    TextEntry::make('country')
                        ->label('Страна')
                        ->icon('heroicon-m-globe-alt'),
                    TextEntry::make('ip')
                        ->label('IP')
                        ->icon('heroicon-m-cursor-arrow-ripple'),
                    TextEntry::make('created_at')
                        ->label('Дата и время создания')
                        ->dateTime(),
                ])->columns(4)->columnSpanFull(),


                SpatieMediaLibraryImageEntry::make('files')
                    ->label('Файлы')
                    ->collection('files'),



            ]);
    }
}
