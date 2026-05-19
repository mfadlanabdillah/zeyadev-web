<?php
    /** @var \App\Filament\Pages\AttendanceMap $this */
?>

<x-filament-panels::page>
    <div class="space-y-4">
        <form wire:submit.prevent="loadMarkers" class="flex items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter Tanggal</label>
                <input
                    type="date"
                    wire:model="filterDate"
                    wire:change="loadMarkers"
                    value="{{ $filterDate }}"
                    class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm"
                >
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400 pb-1">
                {{ count($markers) }} lokasi absensi ditemukan
            </div>
        </form>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div id="attendance-map" style="height: 600px; width: 100%;"></div>
        </div>

        @if (count($markers) > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Daftar Absensi Hari Ini</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Nama</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Check-in</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Check-out</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($markers as $attendance)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 cursor-pointer" onclick="flyToMarker({{ $attendance['check_in_latitude'] }}, {{ $attendance['check_in_longitude'] }})">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $attendance['user']['name'] ?? 'Unknown' }}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                    {{ $attendance['check_in_time'] ? \Carbon\Carbon::parse($attendance['check_in_time'])->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                    {{ $attendance['check_out_time'] ? \Carbon\Carbon::parse($attendance['check_out_time'])->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($attendance['status'] === 'on_time')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-green-100 dark:bg-green-900/30 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:text-green-400">
                                            Tepat Waktu
                                        </span>
                                    @elseif($attendance['status'] === 'late')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-red-100 dark:bg-red-900/30 px-2.5 py-0.5 text-xs font-medium text-red-700 dark:text-red-400">
                                            Terlambat
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 dark:bg-gray-900/30 px-2.5 py-0.5 text-xs font-medium text-gray-700 dark:text-gray-400">
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

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #attendance-map .leaflet-popup-content { margin: 12px 16px; font-size: 13px; line-height: 1.5; }
        #attendance-map .leaflet-popup-content strong { display: block; font-size: 14px; margin-bottom: 4px; }
    </style>
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const markers = @json($markers);
            initMap(markers);
        });

        let map;
        let markerLayer;

        function initMap(data) {
            if (!data.length) {
                document.getElementById('attendance-map').innerHTML = `
                    <div class="flex items-center justify-center h-full text-gray-400 dark:text-gray-500">
                        <div class="text-center">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            <p>Tidak ada data lokasi absensi untuk tanggal ini.</p>
                        </div>
                    </div>
                `;
                return;
            }

            map = L.map('attendance-map').setView([-6.200000, 106.816666], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                maxZoom: 19,
            }).addTo(map);

            markerLayer = L.layerGroup().addTo(map);

            data.forEach(function (attendance) {
                const name = attendance.user?.name || 'Unknown';
                const checkIn = attendance.check_in_time ? new Date(attendance.check_in_time).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) : '-';
                const checkOut = attendance.check_out_time ? new Date(attendance.check_out_time).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) : '-';
                const status = attendance.status === 'on_time' ? '✅ Tepat Waktu' : (attendance.status === 'late' ? '⚠️ Terlambat' : attendance.status);
                const color = attendance.status === 'on_time' ? '#10B981' : (attendance.status === 'late' ? '#EF4444' : '#6B7280');
                const checkInLat = parseFloat(attendance.check_in_latitude);
                const checkInLng = parseFloat(attendance.check_in_longitude);

                if (isNaN(checkInLat) || isNaN(checkInLng)) return;

                const icon = L.divIcon({
                    html: `<div style="background:${color};width:24px;height:24px;border-radius:50%;border:3px solid white;box-shadow:0 2px 6px rgba(0,0,0,0.3);"></div>`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12],
                    popupAnchor: [0, -16],
                    className: ''
                });

                const marker = L.marker([checkInLat, checkInLng], { icon }).addTo(markerLayer);

                marker.bindPopup(`
                    <strong>${name}</strong>
                    <div style="margin-top:4px;">
                        <div>📍 Check-in: <b>${checkIn}</b></div>
                        <div>🚶 Check-out: <b>${checkOut}</b></div>
                        <div style="margin-top:4px;">${status}</div>
                    </div>
                `);
            });

            if (data.length > 0) {
                const group = L.featureGroup(Object.values(markerLayer._layers));
                map.fitBounds(group.getBounds().pad(0.1));
            }
        }

        function flyToMarker(lat, lng) {
            if (map) {
                map.flyTo([lat, lng], 16, { duration: 1 });
            }
        }
    </script>
    @endpush
</x-filament-panels::page>
