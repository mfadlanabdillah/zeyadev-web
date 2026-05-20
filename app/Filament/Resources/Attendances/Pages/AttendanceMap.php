<?php

namespace App\Filament\Resources\Attendances\Pages;

use App\Filament\Resources\Attendances\AttendanceResource;
use App\Models\Attendance;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AttendanceMap extends Page
{
    protected static string $resource = AttendanceResource::class;

    protected string $view = 'filament.resources.attendances.pages.attendance-map';

    public function getAttendances(): Collection
    {
        return Attendance::query()
            ->where(function (Builder $q) {
                $q->whereNotNull('check_in_latitude')
                  ->whereNotNull('check_in_longitude');
            })
            ->orWhere(function (Builder $q) {
                $q->whereNotNull('check_out_latitude')
                  ->whereNotNull('check_out_longitude');
            })
            ->with('user', 'branch')
            ->latest('attendance_date')
            ->limit(500)
            ->get();
    }
}
