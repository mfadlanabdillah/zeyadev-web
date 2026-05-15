<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Api\CheckInRequest;
use App\Http\Requests\Api\CheckOutRequest;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Models\Company;
use App\Http\Resources\AttendanceResource;

class AttendanceController extends Controller
{
    public function checkIn(CheckInRequest $request): JsonResponse
    {
        $user = $request->user();
        $today = Carbon::today();

        $branch = $user->branch;

        if (! $branch) {
            return response()->json([
                'success' => false,
                'message' => 'Branch information not found.'
            ], 404);
        }

        if ($branch->require_geolocation) {
            $distance = $this->calculateDistance(
                $request->latitude,
                $request->longitude,
                $branch->latitude,
                $branch->longitude
            );

            if ($distance > $branch->radius) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are outside the allowed check-in radius. Distance: ' . round($distance, 2) . ' meters.'
                ], 422);
            }
        }

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('attendances/'.$today->format('Y/m'), 'public');
        }

        $now = Carbon::now();
        $workStartTime = Carbon::parse($branch->work_start_time);
        $status = $now->gt($workStartTime->setDate($now->year, $now->month, $now->day)) ? 'late' : 'on_time';

        $maxAttempts = 3;
        $attempt = 0;

        while ($attempt < $maxAttempts) {
            try {
                $attendance = Attendance::create([
                    'user_id' => $user->id,
                    'attendance_date' => $today,
                    'check_in_time' => $now,
                    'check_in_latitude' => $request->latitude,
                    'check_in_longitude' => $request->longitude,
                    'check_in_photo' => $photoPath,
                    'status' => $status,
                ]);
                break;
            } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                $attempt++;
                if ($attempt >= $maxAttempts) {
                    $existing = Attendance::withoutTrashed()
                        ->where('user_id', $user->id)
                        ->where('attendance_date', $today)
                        ->first();

                    if ($existing) {
                        return response()->json([
                            'success' => false,
                            'message' => 'You have already checked in today.'
                        ], 400);
                    }

                    $deleted = Attendance::onlyTrashed()
                        ->where('user_id', $user->id)
                        ->where('attendance_date', $today)
                        ->first();

                    if ($deleted) {
                        $deleted->forceDelete();
                    }

                    return response()->json([
                        'success' => false,
                        'message' => 'Your previous attendance record was removed. Please try again.'
                    ], 409);
                }
                usleep(100000);
            }
        }

        $message = $status === 'late' ? 'Checked in successfully, but you are late.' : 'Checked in successfully on time.';

        return response()->json([
            'success' => true,
            'data' => $attendance,
            'message' => $message
        ]);
    }

    public function checkOut(CheckOutRequest $request): JsonResponse
    {
        $user = $request->user();
        $today = Carbon::today();

        $existingAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', $today)
            ->first();

        if (! $existingAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum check-in hari ini.'
            ], 400);
        }

        $branch = $user->branch;

        if (! $branch) {
            return response()->json([
                'success' => false,
                'message' => 'Branch information not found.'
            ], 404);
        }

        if ($branch->require_geolocation) {
             $distance = $this->calculateDistance(
                $request->latitude,
                $request->longitude,
                $branch->latitude,
                $branch->longitude
            );

            if ($distance > $branch->radius) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are outside the allowed check-out radius. Distance: ' . round($distance, 2) . ' meters.'
                ], 422);
            }
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('attendances/'.$today->format('Y/m'), 'public');
        }

        $existingAttendance = $existingAttendance->update([
            'check_out_time' => Carbon::now(),
            'check_out_latitude' => $request->latitude,
            'check_out_longitude' => $request->longitude,
            'check_out_photo' => $photoPath,
        ]);

        return response()->json([
            'success' => true,
            'data' => $existingAttendance,
            'message' => 'Checked out successfully.'
        ]);

    }

    public function today(Request $request): JsonResponse
    {
        $attendance = Attendance::where('user_id', $request->user()->id)
            ->whereDate('attendance_date', Carbon::today())
            ->first();
        
        return response()->json([
            'success' => true,
            'data' => $attendance,
            'message' => $attendance ? 'Today\'s attendance retrieved successfully.' : 'No attendance record for today.'
        ]);
    }

    public function history(Request $request): JsonResponse
    {
        $month = $request->query('month', Carbon::now()->month);
        $year = $request->query('year', Carbon::now()->year);

        $start = Carbon::createFromDate((int) $year, (int) $month, 1)->startOfDay();
        $today = Carbon::today()->startOfDay();
        $endOfMonth = $start->copy()->endOfMonth()->startOfDay();
        $isCurrentMonth = ((int) $year === (int) $today->year) && ((int) $month === (int) $today->month);
        $end = $isCurrentMonth ? $today : $endOfMonth;

        $attendances = Attendance::where('user_id', $request->user()->id)
            ->whereYear('attendance_date', $year)
            ->whereMonth('attendance_date', $month)
            ->orderBy('attendance_date', 'desc')
            ->get();

        $attendanceMap = $attendances->keyBy(function (Attendance $attendance) {
            return $attendance->attendance_date->format('Y-m-d');
        });

        $fullMonth = [];
        for ($date = $end->copy(); $date->gte($start); $date->subDay()) {
            $key = $date->toDateString();
            $attendance = $attendanceMap->get($key);

            if ($attendance) {
                $item = $attendance->toArray();
                $item['keterangan'] = $item['keterangan'] ?? null;
            } else {
                $item = [
                    'attendance_date' => $key,
                    'status' => 'absent',
                    'keterangan' => 'absent',
                    'check_in_time' => null,
                    'check_in_latitude' => null,
                    'check_in_longitude' => null,
                    'check_in_photo' => null,
                    'check_out_time' => null,
                    'check_out_latitude' => null,
                    'check_out_longitude' => null,
                    'check_out_photo' => null,
                ];
            }

            $fullMonth[] = $item;
        }

        return response()->json([
            'success' => true,
            'data' => $fullMonth,
            'message' => 'Attendance history retrieved successfully.'
        ]);
    }

    private function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000; // Earth's radius in meters

        $lat1Rad = deg2rad($lat1);
        $lat2Rad = deg2rad($lat2);
        $deltaLat = deg2rad($lat2 - $lat1);
        $deltaLon = deg2rad($lon2 - $lon1);

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
            cos($lat1Rad) * cos($lat2Rad) *
            sin($deltaLon / 2) * sin($deltaLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

}
