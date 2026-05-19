<?php

namespace App\Filament\Resources\Branches\Schemas;

use App\Filament\Forms\Components\MapPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class BranchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('company_id')
                    ->relationship('company', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('address'),
                MapPicker::make('coordinates')
                    ->label('Location')
                    ->hiddenLabel()
                    ->columnSpanFull(),
                TextInput::make('latitude')
                    ->required()
                    ->numeric()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('coordinates', $state);
                    }),
                TextInput::make('longitude')
                    ->required()
                    ->numeric()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('coordinates', $state);
                    }),
                TextInput::make('radius')
                    ->required()
                    ->numeric()
                    ->default(100),
                TimePicker::make('work_start_time')
                    ->required(),
                TimePicker::make('work_end_time')
                    ->required(),
                Toggle::make('require_liveness')
                    ->label('Require Liveness Detection')
                    ->inline(false)
                    ->default(true),
                Toggle::make('require_geolocation')
                    ->label('Require Geolocation Validation')
                    ->inline(false)
                    ->default(true),
                Toggle::make('require_face_recognition')
                    ->label('Require Face Recognition')
                    ->inline(false)
                    ->default(true),
            ])
            ->columns(2);
    }
}
