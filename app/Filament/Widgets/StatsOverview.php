<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\User;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        $user = auth()->user();

        return $user?->hasRole('super_admin') ?? false;
    }

    protected function getStats(): array
    {
        $today = Carbon::today();
        $totalEmployees = User::role('employee')->count();

        $attendanceToday = Attendance::whereDate('attendance_date', $today);
        $presentToday = $attendanceToday->count();
        $lateToday = (clone $attendanceToday)->where('status', 'late')->count();
        $onTimeToday = (clone $attendanceToday)->where('status', 'on_time')->count();
        $notCheckedIn = $totalEmployees - $presentToday;


        return [
            Stat::make('Total Karyawan', $totalEmployees)
                ->description('Karyawan terdaftar')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),
            Stat::make('Hadir Hari Ini', $presentToday)
                ->description("{$onTimeToday} Tepat Waktu, {$lateToday} Terlambat")
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Belum Absen', $notCheckedIn)
                ->description('Hari ini')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }
}