<?php

namespace App\Filament\Pages;

use App\Models\Attendance;
use Carbon\Carbon;
use BackedEnum;
use Filament\Pages\Page;

class AttendanceMap extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-map';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.attendance-map';

    public array $markers = [];

    public ?string $filterDate = null;

    public int $totalAttendanceToday = 0;

    public int $onTimeCount = 0;

    public int $lateCount = 0;

    public function mount(): void
    {
        $this->filterDate = request('date', Carbon::today()->format('Y-m-d'));
        $this->loadMarkers();
    }

    public function loadMarkers(): void
    {
        $user = auth()->user();
        $date = $this->filterDate ? Carbon::parse($this->filterDate) : Carbon::today();

        $query = Attendance::query()
            ->with('user')
            ->whereDate('attendance_date', $date);

        if (!$user?->hasRole('super_admin')) {
            $query->where('user_id', $user->id);
        }

        $attendances = $query->get();

        $this->totalAttendanceToday = $attendances->count();
        $this->onTimeCount = $attendances->where('status', 'on_time')->count();
        $this->lateCount = $attendances->where('status', 'late')->count();

        $this->markers = $attendances
            ->filter(fn ($a) => $a->check_in_latitude && $a->check_in_longitude)
            ->values()
            ->toArray();
    }

    public function updatedFilterDate(): void
    {
        $this->loadMarkers();
    }

    public static function getNavigationLabel(): string
    {
        return 'Peta Absensi';
    }
}
