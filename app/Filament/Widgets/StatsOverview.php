<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Company;
use App\Models\Branch;

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
        $totalCompanies = Company::count();
        $totalBranches = Branch::count();

        $attendanceToday = Attendance::whereDate('attendance_date', $today);
        $presentToday = (clone $attendanceToday)->count();
        $lateToday = (clone $attendanceToday)->where('status', 'late')->count();
        $onTimeToday = (clone $attendanceToday)->where('status', 'on_time')->count();
        $notCheckedIn = $totalEmployees > 0 ? max(0, $totalEmployees - $presentToday) : 0;

        $attendanceRate = $totalEmployees > 0 ? round(($presentToday / $totalEmployees) * 100) : 0;

        $yesterdayAttendance = Attendance::whereDate('attendance_date', Carbon::yesterday())->count();
        $trend = $yesterdayAttendance > 0
            ? round((($presentToday - $yesterdayAttendance) / $yesterdayAttendance) * 100, 1)
            : 0;

        return [
            Stat::make('Total Karyawan', $totalEmployees)
                ->description($totalCompanies . ' Perusahaan | ' . $totalBranches . ' Cabang')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Hadir Hari Ini', $presentToday)
                ->description("{$onTimeToday} Tepat Waktu, {$lateToday} Terlambat")
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Tingkat Kehadiran', $attendanceRate . '%')
                ->description($trend > 0 ? "Naik {$trend}% dari kemarin" : ($trend < 0 ? "Turun {$trend}% dari kemarin" : 'Sama seperti kemarin'))
                ->descriptionIcon($trend > 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->color($trend > 0 ? 'success' : ($trend < 0 ? 'danger' : 'warning')),

            Stat::make('Belum Absen', $notCheckedIn)
                ->description('Karyawan hari ini')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }
}
