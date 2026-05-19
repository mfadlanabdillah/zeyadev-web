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
        $absentData = collect();

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels->push($date->format('d M'));

            $dayAttendance = Attendance::whereDate('attendance_date', $date);
            $onTime = (clone $dayAttendance)->where('status', 'on_time')->count();
            $late = (clone $dayAttendance)->where('status', 'late')->count();

            $onTimeData->push($onTime);
            $lateData->push($late);
        }

        return [
            'labels' => $labels->toArray(),
            'datasets' => [
                [
                    'label' => 'Tepat Waktu',
                    'data' => $onTimeData->toArray(),
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 3,
                    'pointHoverRadius' => 6,
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Terlambat',
                    'data' => $lateData->toArray(),
                    'borderColor' => '#EF4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 3,
                    'pointHoverRadius' => 6,
                    'borderWidth' => 2,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'interaction' => [
                'intersect' => false,
                'mode' => 'index',
            ],
        ];
    }
}
