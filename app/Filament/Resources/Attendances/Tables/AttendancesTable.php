<?php

namespace App\Filament\Resources\Attendances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('attendance_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('check_in_time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('check_in_latitude')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('check_in_longitude')
                    ->numeric()
                    ->sortable(),
                ImageColumn::make('check_in_photo')
                    ->disk('public')
                    ->label('Check In Photo')
                    ->height(60)
                    ->width(60),
                TextColumn::make('check_out_time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('check_out_latitude')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('check_out_longitude')
                    ->numeric()
                    ->sortable(),
                ImageColumn::make('check_out_photo')
                    ->disk('public')
                    ->label('Check Out Photo')
                    ->height(60)
                    ->width(60),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
