<?php
    /** @var \App\Filament\Pages\AttendanceMap $this */
?>

<x-filament-panels::page>
    {{-- Toolbar --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Peta Absensi</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ \Carbon\Carbon::parse($filterDate)->locale('id')->translatedFormat('l, d F Y') }}
            </p>
        </div>
        <input
            type="date"
            wire:model.live="filterDate"
            value="{{ $filterDate }}"
            class="block rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 text-sm"
        >
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Lokasi</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ count($markers) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Tepat Waktu</p>
                    <p class="text-xl font-bold text-green-600 dark:text-green-400">{{ $onTimeCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Terlambat</p>
                    <p class="text-xl font-bold text-red-600 dark:text-red-400">{{ $lateCount }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Map --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Lokasi Check-in</span>
            </div>
            <div class="flex items-center gap-3 text-xs text-gray-500">
                <span class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-green-500 inline-block"></span>
                    Tepat Waktu
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500 inline-block"></span>
                    Terlambat
                </span>
            </div>
        </div>
        <div id="attendance-map" class="w-full" style="height: 450px;"></div>
    </div>

    {{-- Table --}}
    @if (count($markers) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3 border-b border-gray-200 dark:border-gray-700">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Daftar Absensi</span>
                <span class="text-xs text-gray-500">{{ count($markers) }} orang</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/30">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Karyawan</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Check-in</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Check-out</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @foreach($markers as $attendance)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 cursor-pointer transition-colors" onclick="flyToMarker({{ $attendance['check_in_latitude'] }}, {{ $attendance['check_in_longitude'] }})">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-amber-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                        {{ strtoupper(substr($attendance['user']['name'] ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $attendance['user']['name'] ?? 'Unknown' }}</p>
                                        <p class="text-xs text-gray-500">{{ $attendance['user']['employee_id'] ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <span class="font-medium text-gray-900 dark:text-white">{{ $attendance['check_in_time'] ? \Carbon\Carbon::parse($attendance['check_in_time'])->format('H:i') : '-' }}</span>
                            </td>
                            <td class="px-5 py-3 text-gray-500 dark:text-gray-400">
                                {{ $attendance['check_out_time'] ? \Carbon\Carbon::parse($attendance['check_out_time'])->format('H:i') : '-' }}
                            </td>
                            <td class="px-5 py-3">
                                @if($attendance['status'] === 'on_time')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-green-50 dark:bg-green-900/20 px-3 py-1 text-xs font-semibold text-green-700 dark:text-green-400">
                                        Tepat Waktu
                                    </span>
                                @elseif($attendance['status'] === 'late')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-red-50 dark:bg-red-900/20 px-3 py-1 text-xs font-semibold text-red-700 dark:text-red-400">
                                        Terlambat
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 dark:bg-gray-800 px-3 py-1 text-xs font-semibold text-gray-600 dark:text-gray-400">
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

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #attendance-map .leaflet-popup-content { margin: 12px 16px; font-size: 13px; line-height: 1.6; }
        #attendance-map .leaflet-popup-content strong { display: block; font-size: 14px; margin-bottom: 4px; }
        .dark #attendance-map .leaflet-popup-content-wrapper { background: #1f2937; color: #d1d5db; }
        .dark #attendance-map .leaflet-popup-tip { background: #1f2937; }
    </style>
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('livewire:navigated', () => {
            const data = @json($markers);
            initMap(data);
        });

        let map;
        let markers = [];

        function initMap(data) {
            const el = document.getElementById('attendance-map');
            if (!el) return;
            if (map) { map.remove(); map = null; }
            if (!data.length) {
                el.innerHTML = `
                    <div class="flex items-center justify-center h-full text-gray-400 dark:text-gray-500">
                        <div class="text-center">
                            <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            <p>Belum ada data absensi untuk tanggal ini</p>
                        </div>
                    </div>
                `;
                return;
            }

            map = L.map('attendance-map').setView([-6.2, 106.82], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(map);

            markers = [];

            data.forEach(function(attendance) {
                const lat = parseFloat(attendance.check_in_latitude);
                const lng = parseFloat(attendance.check_in_longitude);
                if (isNaN(lat) || isNaN(lng)) return;

                const name = attendance.user?.name || 'Unknown';
                const checkIn = attendance.check_in_time ? new Date(attendance.check_in_time).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) : '-';
                const checkOut = attendance.check_out_time ? new Date(attendance.check_out_time).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) : '-';
                const isOnTime = attendance.status === 'on_time';
                const color = isOnTime ? '#10B981' : '#EF4444';

                const icon = L.divIcon({
                    html: `<div style="width:24px;height:24px;background:${color};border:3px solid white;border-radius:50%;box-shadow:0 2px 8px rgba(0,0,0,0.3);"></div>`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12],
                    popupAnchor: [0, -16],
                    className: ''
                });

                const marker = L.marker([lat, lng], { icon }).addTo(map);
                markers.push(marker);

                marker.bindPopup(`
                    <strong>${name}</strong>
                    <div style="margin-top:4px;">
                        <div>Check-in: <b>${checkIn}</b></div>
                        <div>Check-out: <b>${checkOut}</b></div>
                        <div style="margin-top:4px;">${isOnTime ? '✅ Tepat Waktu' : '⚠️ Terlambat'}</div>
                    </div>
                `);
            });

            if (markers.length > 0) {
                const group = L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.1));
            }

            map.invalidateSize();
        }

        function flyToMarker(lat, lng) {
            if (map) {
                map.flyTo([lat, lng], 17, { duration: 0.8 });
                markers.forEach(function(m) {
                    if (m.getLatLng().lat === lat && m.getLatLng().lng === lng) {
                        m.openPopup();
                    }
                });
            }
        }
    </script>
    @endpush
</x-filament-panels::page>
