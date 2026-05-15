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
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('user.employee_id')
                    ->label('ID Karyawan')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('check_in_time')
                    ->label('Check-in')
                    ->dateTime('H:i')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('check_out_time')
                    ->label('Check-out')
                    ->dateTime('H:i')
                    ->placeholder('-')
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'on_time',
                        'warning' => 'late',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'on_time' => 'Tepat Waktu',
                        'late' => 'Terlambat',
                        default => $state,
                    }),
            ])
            ->defaultSort('check_in_time', 'desc');
    }
}
