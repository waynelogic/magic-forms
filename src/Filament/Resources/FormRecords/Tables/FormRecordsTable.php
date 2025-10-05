<?php

namespace Waynelogic\MagicForms\Filament\Resources\FormRecords\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FormRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('manager.name')
                    ->label('Менеджер')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('group')
                    ->label('Группа')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('unread')
                    ->label('Непрочитано')
                    ->boolean(),
                TextColumn::make('form_data')
                    ->label('Данные')
                    ->separator('|'),
                TextColumn::make('ip')
                    ->label('IP')
                    ->searchable(),
                TextColumn::make('country')
                    ->label('Страна')
                    ->searchable(),
                TextColumn::make('city')
                    ->label('Город')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Дата и время отправки')
                    ->weight(FontWeight::Black)
                    ->dateTime('d M Y - H:i')
                    ->sortable()
                    ->badge()
                    ->color(fn($record) => $record->unread ? 'primary' : 'gray')
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label('Дата и время обновления')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
