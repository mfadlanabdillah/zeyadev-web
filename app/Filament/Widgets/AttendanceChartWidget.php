<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use App\Models\Attendance;

class AttendanceChartWidget extends ChartWidget
{
    protected ?string $heading = 'Statistik Kehadiran 30 Hari Terakhir';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = auth()->user();

        return $user?->hasRole('super_admin') ?? false;
    }

    protected function getData(): array
    {

        $data = collect();
        $labels = collect();
        $lateData = collect();
        $onTimeData = collect();

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels->push($date->format('d M'));

            $dayAttendance = Attendance::whereDate('attendance_date', $date);
            $onTimeData->push((clone $dayAttendance)->where('status', 'on_time')->count());
            $lateData->push((clone $dayAttendance)->where('status', 'late')->count());
        }


        return [
            'labels' => $labels->toArray(),
            'datasets' => [
                [
                    'label' => 'Tepat Waktu',
                    'data' => $onTimeData->toArray(),
                    'backgroundColor' => '#10B981', // Green
                    'borderColor' => '#10B981',
                ],
                [
                    'label' => 'Terlambat',
                    'data' => $lateData->toArray(),
                    'backgroundColor' => '#EF4444', // Red
                    'borderColor' => '#EF4444',
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
