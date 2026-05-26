<?php
    /** @var \App\Filament\Pages\AttendanceMap $this */
?>

<div class="space-y-6">
    {{-- Editorial stats row — signature accent bars --}}
    <div class="grid grid-cols-3 gap-5">
        <div class="relative bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="absolute inset-x-0 top-0 h-1" style="background-color: #181d26;"></div>
            <div class="p-5">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Absensi</p>
                <p class="mt-2 text-3xl font-normal text-gray-900 tracking-tight">{{ $totalAttendanceToday }}</p>
                <div class="mt-3 flex items-center gap-2 text-xs text-gray-400">
                    <span class="inline-block w-2 h-2 rounded-full bg-gray-400"></span>
                    <span>{{ count($markers) }} with location</span>
                </div>
            </div>
        </div>
        <div class="relative bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="absolute inset-x-0 top-0 h-1" style="background-color: #006400;"></div>
            <div class="p-5">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tepat Waktu</p>
                <p class="mt-2 text-3xl font-normal text-gray-900 tracking-tight">{{ $onTimeCount }}</p>
                <div class="mt-3 flex items-center gap-2 text-xs text-gray-400">
                    <span class="inline-block w-2 h-2 rounded-full" style="background-color: #006400;"></span>
                    <span>{{ $totalAttendanceToday > 0 ? round(($onTimeCount / max($totalAttendanceToday, 1)) * 100) : 0 }}% on time</span>
                </div>
            </div>
        </div>
        <div class="relative bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="absolute inset-x-0 top-0 h-1" style="background-color: #aa2d00;"></div>
            <div class="p-5">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Terlambat</p>
                <p class="mt-2 text-3xl font-normal text-gray-900 tracking-tight">{{ $lateCount }}</p>
                <div class="mt-3 flex items-center gap-2 text-xs text-gray-400">
                    <span class="inline-block w-2 h-2 rounded-full" style="background-color: #aa2d00;"></span>
                    <span>{{ $totalAttendanceToday > 0 ? round(($lateCount / max($totalAttendanceToday, 1)) * 100) : 0 }}% late</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Editorial date filter bar --}}
    <div class="relative bg-white rounded-lg border border-gray-200 px-5 py-4 flex items-center justify-between">
        <div class="absolute inset-y-0 left-0 w-0.5" style="background-color: #fcab79;"></div>
        <div class="flex items-center gap-4">
            <div class="flex items-center justify-center w-10 h-10 rounded-full" style="background-color: #f5e9d4;">
                <svg class="w-5 h-5" style="color: #aa2d00;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-900">Peta Absensi</p>
                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($filterDate)->locale('id')->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>
        <div>
            <input type="date" wire:model.live="filterDate" value="{{ $filterDate }}"
                   class="appearance-none bg-white border border-gray-200 rounded-md px-4 py-2.5 text-sm text-gray-900 font-medium focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-colors"
                   style="height: 44px;">
        </div>
    </div>

    {{-- Map card --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100">
            <div class="flex items-center gap-4 text-xs text-gray-400">
                <span class="flex items-center gap-1.5">
                    <span class="inline-block w-2.5 h-2.5 rounded-full" style="background-color: #006400;"></span> Tepat Waktu
                </span>
                <span class="text-gray-200">|</span>
                <span class="flex items-center gap-1.5">
                    <span class="inline-block w-2.5 h-2.5 rounded-full" style="background-color: #aa2d00;"></span> Terlambat
                </span>
            </div>
            <span class="text-xs text-gray-400 font-medium">{{ count($markers) }} lokasi</span>
        </div>
        <div id="attendance-map" class="w-full" style="height: 420px;"></div>
        <script id="attendance-map-data" type="application/json">@json($markers)</script>
    </div>

    {{-- Attendance table --}}
    @if (count($markers) > 0)
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
                <p class="text-sm font-medium text-gray-900">Daftar Absensi</p>
                <span class="text-xs text-gray-400">{{ count($markers) }} karyawan</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50/50 border-b border-gray-100">Karyawan</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50/50 border-b border-gray-100">Check-in</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50/50 border-b border-gray-100">Check-out</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50/50 border-b border-gray-100">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($markers as $index => $attendance)
                            <tr wire:key="{{ $index }}"
                                class="hover:bg-gray-50/80 cursor-pointer transition-colors"
                                onclick="flyToMarker({{ $attendance['check_in_latitude'] }}, {{ $attendance['check_in_longitude'] }})">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-xs font-semibold text-gray-500 uppercase">
                                            {{ strtoupper(substr($attendance['user']['name'] ?? 'U', 0, 1)) }}
                                        </div>
                                        <span class="font-medium text-gray-900">{{ $attendance['user']['name'] ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-gray-600">
                                    {{ $attendance['check_in_time'] ? \Carbon\Carbon::parse($attendance['check_in_time'])->format('H:i') : '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-gray-400">
                                    {{ $attendance['check_out_time'] ? \Carbon\Carbon::parse($attendance['check_out_time'])->format('H:i') : '-' }}
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($attendance['status'] === 'on_time')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-sm text-xs font-medium text-white" style="background-color: #006400;">
                                            Tepat Waktu
                                        </span>
                                    @elseif($attendance['status'] === 'late')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-sm text-xs font-medium text-white" style="background-color: #aa2d00;">
                                            Terlambat
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-sm text-xs font-medium bg-gray-100 text-gray-500">
                                            {{ $attendance['status'] }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<style>
    #attendance-map .leaflet-popup-content { margin: 12px 16px; font-size: 13px; line-height: 1.6; font-family: Inter, sans-serif; }
    #attendance-map .leaflet-popup-content strong { display: block; font-size: 14px; font-weight: 500; color: #181d26; margin-bottom: 4px; }
    #attendance-map .leaflet-popup-content-wrapper { border-radius: 10px; box-shadow: none; border: 1px solid #dddddd; }
    #attendance-map .leaflet-popup-tip { border: 1px solid #dddddd; }
    .dark #attendance-map .leaflet-popup-content-wrapper { background: #1d1f25; color: #cccccc; border-color: #333840; }
    .dark #attendance-map .leaflet-popup-tip { background: #1d1f25; border-color: #333840; }
    .dark #attendance-map .leaflet-popup-close-button { color: #6b7280; }
    #attendance-map .leaflet-control-zoom a { border: 1px solid #dddddd; color: #333840; background: #ffffff; }
    .dark #attendance-map .leaflet-control-zoom a { background: #1d1f25; color: #cccccc; border-color: #333840; }
    #attendance-map .leaflet-control-attribution { font-size: 11px; color: #9297a0; }
</style>

<script>
let attendanceMapInstance = null
let attendanceMapMarkers = []

document.addEventListener('livewire:navigated', initAttendanceMap)
if (document.readyState !== 'loading') setTimeout(initAttendanceMap, 100)

function initAttendanceMap() {
    if (typeof L === 'undefined') return

    const el = document.getElementById('attendance-map')
    if (!el) return

    let data = []
    try {
        const script = document.getElementById('attendance-map-data')
        data = script ? JSON.parse(script.textContent) : []
    } catch(e) { data = [] }

    if (attendanceMapInstance) {
        attendanceMapInstance.remove()
        attendanceMapInstance = null
    }
    attendanceMapMarkers = []

    if (!data || !data.length) {
        el.innerHTML = '<div class="flex flex-col items-center justify-center h-full text-gray-400 gap-3"><svg class="w-10 h-10 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg><p class="text-sm font-medium">Belum ada data absensi untuk tanggal ini</p></div>'
        return
    }

    attendanceMapInstance = L.map('attendance-map').setView([-6.2, 106.82], 12)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(attendanceMapInstance)

    data.forEach(function(attendance) {
        const lat = parseFloat(attendance.check_in_latitude)
        const lng = parseFloat(attendance.check_in_longitude)
        if (isNaN(lat) || isNaN(lng)) return
        const isOn = attendance.status === 'on_time'
        const color = isOn ? '#006400' : '#aa2d00'
        const statusLabel = isOn ? 'Tepat Waktu' : 'Terlambat'

        const icon = L.divIcon({
            html: `<div style="width:24px;height:24px;background:${color};border:2px solid white;border-radius:50%;box-shadow:0 1px 4px rgba(0,0,0,0.2);display:flex;align-items:center;justify-content:center;"><div style="width:6px;height:6px;background:white;border-radius:50%;"></div></div>`,
            iconSize: [24, 24], iconAnchor: [12, 12], popupAnchor: [0, -16], className: ''
        })

        const marker = L.marker([lat, lng], { icon }).addTo(attendanceMapInstance)
        attendanceMapMarkers.push(marker)

        const popupContent = `
            <div style="min-width: 160px;">
                <strong>${attendance.user?.name || 'Unknown'}</strong>
                <div style="display:flex;align-items:center;gap:6px;margin-top:4px;">
                    <span style="width:8px;height:8px;background:${color};border-radius:50%;"></span>
                    <span style="font-size:12px;color:#9297a0;">${statusLabel}</span>
                </div>
                <div style="margin-top:8px;font-size:12px;color:#9297a0;">
                    <div>Check-in: <strong style="color:#333840;">${attendance.check_in_time ? new Date(attendance.check_in_time).toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'}) : '-'}</strong></div>
                    ${attendance.check_out_time ? `<div style="margin-top:2px;">Check-out: <strong style="color:#333840;">${new Date(attendance.check_out_time).toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'})}</strong></div>` : ''}
                </div>
            </div>
        `
        marker.bindPopup(popupContent)
    })

    if (attendanceMapMarkers.length) {
        const group = L.featureGroup(attendanceMapMarkers)
        attendanceMapInstance.fitBounds(group.getBounds().pad(0.1))
    }
    attendanceMapInstance.invalidateSize()
}

function flyToMarker(lat, lng) {
    if (!attendanceMapInstance) return
    attendanceMapInstance.flyTo([lat, lng], 17, { duration: 0.8 })
    attendanceMapMarkers.forEach(m => {
        if (m.getLatLng().lat === lat && m.getLatLng().lng === lng) m.openPopup()
    })
}
</script>
