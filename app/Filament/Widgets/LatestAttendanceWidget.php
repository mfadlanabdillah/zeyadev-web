<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Filament\Tables;
use Carbon\Carbon;

class LatestAttendanceWidget extends TableWidget
{
    protected static ?string $heading = 'Kehadiran Hari Ini';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $user = auth()->user();

        $isSuperAdmin = $user?->hasRole('super_admin') ?? false;
        $query = Attendance::query()
            ->with('user')
            ->whereDate('attendance_date', Carbon::today())
            ->latest('check_in_time');
        if (! $isSuperAdmin && $user) {
            $query->where('user_id', $user->id);
        }
        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('user.employee_id')
                    ->label('ID')
                    ->searchable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('check_in_time')
                    ->label('Check-in')
                    ->dateTime('H:i:s')
                    ->sortable()
                    ->icon('heroicon-o-arrow-right-end-on-rectangle')
                    ->color('success'),

                Tables\Columns\TextColumn::make('check_out_time')
                    ->label('Check-out')
                    ->dateTime('H:i:s')
                    ->placeholder('-')
                    ->sortable()
                    ->icon('heroicon-o-arrow-left-start-on-rectangle')
                    ->color('danger'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'on_time',
                        'warning' => 'late',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'on_time',
                        'heroicon-o-exclamation-triangle' => 'late',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'on_time' => 'Tepat Waktu',
                        'late' => 'Terlambat',
                        default => $state,
                    }),
            ])
            ->defaultSort('check_in_time', 'desc')
            ->striped()
            ->poll('30s');
    }
}
