<?php
    /** @var \App\Filament\Pages\AttendanceMap $this */
?>

<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg shadow-blue-500/25">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        Peta Absensi
                    </h2>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                        {{ \Carbon\Carbon::parse($filterDate)->locale('id')->translatedFormat('l, d F Y') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <input type="date" wire:model.live="filterDate" value="{{ $filterDate }}"
                           class="pl-4 pr-10 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white bg-white dark:bg-gray-800 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Absensi</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ count($markers) }}</p>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/20">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tepat Waktu</p>
                        <p class="mt-2 text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ $onTimeCount }}</p>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/20">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Terlambat</p>
                        <p class="mt-2 text-3xl font-bold text-rose-600 dark:text-rose-400">{{ $lateCount }}</p>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-rose-50 dark:bg-rose-900/20">
                        <svg class="w-6 h-6 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Map Card --}}
        <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <div class="flex items-center gap-4">
                    <span class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                        <span class="inline-block w-3 h-3 rounded-full bg-emerald-500"></span> Tepat Waktu
                    </span>
                    <span class="text-gray-300 dark:text-gray-600">|</span>
                    <span class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                        <span class="inline-block w-3 h-3 rounded-full bg-rose-500"></span> Terlambat
                    </span>
                </div>
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ count($markers) }} lokasi</span>
            </div>
            <div id="attendance-map" class="w-full" style="height: 420px;"></div>
            <script id="attendance-map-data" type="application/json">@json($markers)</script>
        </div>

        {{-- Attendance Table --}}
        @if (count($markers) > 0)
            <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Daftar Absensi</p>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ count($markers) }} karyawan</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50/50 dark:bg-gray-800/50">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Karyawan</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Check-in</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Check-out</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($markers as $index => $attendance)
                                <tr wire:key="{{ $index }}"
                                    class="hover:bg-blue-50/50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors"
                                    onclick="flyToMarker({{ $attendance['check_in_latitude'] }}, {{ $attendance['check_in_longitude'] }})">
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center justify-center w-9 h-9 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 text-gray-700 dark:text-gray-300 text-xs font-bold">
                                                {{ strtoupper(substr($attendance['user']['name'] ?? 'U', 0, 1)) }}
                                            </div>
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $attendance['user']['name'] ?? 'Unknown' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">
                                        {{ $attendance['check_in_time'] ? \Carbon\Carbon::parse($attendance['check_in_time'])->format('H:i') : '-' }}
                                    </td>
                                    <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400">
                                        {{ $attendance['check_out_time'] ? \Carbon\Carbon::parse($attendance['check_out_time'])->format('H:i') : '-' }}
                                    </td>
                                    <td class="px-5 py-3.5">
                                        @if($attendance['status'] === 'on_time')
                                            <span class="inline-flex items-center rounded-full bg-emerald-50 dark:bg-emerald-900/20 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:text-emerald-400">
                                                Tepat Waktu
                                            </span>
                                        @elseif($attendance['status'] === 'late')
                                            <span class="inline-flex items-center rounded-full bg-rose-50 dark:bg-rose-900/20 px-2.5 py-1 text-xs font-semibold text-rose-700 dark:text-rose-400">
                                                Terlambat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-700 px-2.5 py-1 text-xs font-semibold text-gray-600 dark:text-gray-400">
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
        #attendance-map .leaflet-popup-content { margin: 12px 16px; font-size: 13px; line-height: 1.6; }
        #attendance-map .leaflet-popup-content strong { display: block; font-size: 14px; margin-bottom: 4px; }
        .dark #attendance-map .leaflet-popup-content-wrapper { background: #1f2937; color: #d1d5db; border-radius: 8px; }
        .dark #attendance-map .leaflet-popup-tip { background: #1f2937; }
        .dark #attendance-map .leaflet-control-zoom a { background: #1f2937; color: #d1d5db; border-color: #374151; }
        .dark #attendance-map .leaflet-control-zoom a:hover { background: #374151; }
        #attendance-map .leaflet-popup-close-button { color: #6b7280; }
        .dark #attendance-map .leaflet-popup-close-button { color: #9ca3af; }
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
            el.innerHTML = '<div class="flex flex-col items-center justify-center h-full text-gray-400 gap-2"><svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg><p class="text-sm">Belum ada data absensi untuk tanggal ini</p></div>'
            return
        }

        attendanceMapInstance = L.map('attendance-map').setView([-6.2, 106.82], 12)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(attendanceMapInstance)

        data.forEach(function(attendance) {
            const lat = parseFloat(attendance.check_in_latitude)
            const lng = parseFloat(attendance.check_in_longitude)
            if (isNaN(lat) || isNaN(lng)) return
            const isOn = attendance.status === 'on_time'
            const color = isOn ? '#10B981' : '#EF4444'
            const statusLabel = isOn ? 'Tepat Waktu' : 'Terlambat'

            const icon = L.divIcon({
                html: `<div style="width:28px;height:28px;background:${color};border:3px solid white;border-radius:50%;box-shadow:0 2px 8px rgba(0,0,0,0.25);display:flex;align-items:center;justify-content:center;"><div style="width:8px;height:8px;background:white;border-radius:50%;"></div></div>`,
                iconSize: [28, 28], iconAnchor: [14, 14], popupAnchor: [0, -18], className: ''
            })

            const marker = L.marker([lat, lng], { icon }).addTo(attendanceMapInstance)
            attendanceMapMarkers.push(marker)

            const popupContent = `
                <div style="min-width: 140px;">
                    <strong style="color: #111827;">${attendance.user?.name || 'Unknown'}</strong>
                    <div style="display: flex; align-items: center; gap: 6px; margin-top: 6px;">
                        <span style="width: 8px; height: 8px; background: ${color}; border-radius: 50%;"></span>
                        <span style="font-size: 12px; color: #6b7280;">${statusLabel}</span>
                    </div>
                    <div style="margin-top: 8px; font-size: 12px; color: #6b7280;">
                        <div>Check-in: <strong style="color: #374151;">${attendance.check_in_time ? new Date(attendance.check_in_time).toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'}) : '-'}</strong></div>
                        ${attendance.check_out_time ? `<div style="margin-top: 2px;">Check-out: <strong style="color: #374151;">${new Date(attendance.check_out_time).toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'})}</strong></div>` : ''}
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
</x-filament-panels::page>
