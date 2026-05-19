<?php
    /** @var \App\Filament\Pages\AttendanceMap $this */
?>

<x-filament-panels::page>
    <div class="space-y-6" x-data="{ loading: false }" x-on:livewire:navigating.window="loading = true">
        {{-- Header + Filter --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Peta Absensi</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Pantau lokasi absensi karyawan secara real-time</p>
            </div>

            <div class="flex items-stretch sm:items-center gap-3">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <input
                        type="date"
                        wire:model.live="filterDate"
                        value="{{ $filterDate }}"
                        class="block w-full sm:w-auto pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors"
                    >
                </div>

                <button
                    onclick="window.location.href='{{ request()->url() }}'"
                    class="inline-flex items-center gap-1.5 px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                    title="Hari ini"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            <div class="flex items-center gap-3 px-4 py-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ count($markers) }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 px-4 py-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tepat Waktu</p>
                    <p class="text-lg font-bold text-green-600 dark:text-green-400">{{ $onTimeCount }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 px-4 py-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Terlambat</p>
                    <p class="text-lg font-bold text-red-600 dark:text-red-400">{{ $lateCount }}</p>
                </div>
            </div>
        </div>

        {{-- Map + Table --}}
        <div class="flex flex-col lg:flex-row gap-4">
            {{-- Map --}}
            <div class="flex-1 min-w-0">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Lokasi Absensi</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                            <span class="flex items-center gap-1">
                                <span class="w-2.5 h-2.5 rounded-full bg-green-500 inline-block"></span> Tepat Waktu
                            </span>
                            <span class="flex items-center gap-1">
                                <span class="w-2.5 h-2.5 rounded-full bg-red-500 inline-block"></span> Terlambat
                            </span>
                        </div>
                    </div>
                    <div id="attendance-map" class="min-h-[50vh] sm:min-h-[55vh] lg:min-h-[65vh] w-full"
                         x-init="() => { $nextTick(() => initMap(@json($markers))) }"
                         wire:ignore>
                    </div>
                </div>
            </div>

            {{-- Table Sidebar --}}
            @if (count($markers) > 0)
                <div class="w-full lg:w-80 xl:w-96 flex-shrink-0">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Daftar Absensi</h3>
                        </div>
                        <div class="overflow-y-auto max-h-[50vh] lg:max-h-[60vh] scrollbar-thin">
                            <table class="w-full text-sm">
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                                    @foreach($markers as $i => $attendance)
                                    <tr class="hover:bg-amber-50 dark:hover:bg-amber-900/10 cursor-pointer transition-colors duration-150"
                                        onclick="flyToMarker({{ $attendance['check_in_latitude'] }}, {{ $attendance['check_in_longitude'] }})"
                                        x-data
                                        x-on:mouseenter="$dispatch('highlight-marker', {{ $i }})"
                                        x-on:mouseleave="$dispatch('unhighlight-marker', {{ $i }})">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                                    {{ substr($attendance['user']['name'] ?? 'U', 0, 1) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-medium text-gray-900 dark:text-white truncate max-w-[140px]">
                                                        {{ $attendance['user']['name'] ?? 'Unknown' }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $attendance['user']['employee_id'] ?? '' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 py-3 text-right">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $attendance['check_in_time'] ? \Carbon\Carbon::parse($attendance['check_in_time'])->format('H:i') : '-' }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $attendance['check_out_time'] ? \Carbon\Carbon::parse($attendance['check_out_time'])->format('H:i') : '—' }}
                                            </div>
                                        </td>
                                        <td class="px-3 py-3">
                                            @if($attendance['status'] === 'on_time')
                                                <span class="inline-flex items-center justify-center w-2 h-2 rounded-full bg-green-500 flex-shrink-0"></span>
                                            @elseif($attendance['status'] === 'late')
                                                <span class="inline-flex items-center justify-center w-2 h-2 rounded-full bg-red-500 flex-shrink-0"></span>
                                            @else
                                                <span class="inline-flex items-center justify-center w-2 h-2 rounded-full bg-gray-400 flex-shrink-0"></span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #attendance-map .leaflet-popup-content-wrapper {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        #attendance-map .leaflet-popup-content {
            margin: 14px 18px;
            font-size: 13px;
            line-height: 1.6;
        }
        #attendance-map .leaflet-popup-content strong {
            display: block;
            font-size: 14px;
            margin-bottom: 4px;
            color: #1f2937;
        }
        .dark #attendance-map .leaflet-popup-content strong {
            color: #f3f4f6;
        }
        .dark #attendance-map .leaflet-popup-content-wrapper {
            background: #1f2937;
            color: #d1d5db;
        }
        .dark #attendance-map .leaflet-popup-tip {
            background: #1f2937;
        }
        .leaflet-marker-icon {
            transition: transform 0.2s ease;
        }
        .leaflet-marker-icon:hover {
            transform: scale(1.25);
            z-index: 1000 !important;
        }
        @media (max-width: 640px) {
            #attendance-map {
                min-height: 40vh;
            }
        }
        .scrollbar-thin::-webkit-scrollbar {
            width: 4px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }
        .dark .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #4b5563;
        }
        @keyframes marker-bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.3); }
        }
        .marker-highlight {
            animation: marker-bounce 0.4s ease;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let map;
        let markerLayer;
        let markers = [];

        document.addEventListener('livewire:navigated', () => {
            if (typeof L !== 'undefined' && document.getElementById('attendance-map')) {
                const data = @json($markers);
                initMap(data);
            }
        });

        window.initMap = function(data) {
            if (!document.getElementById('attendance-map')) return;

            if (map) {
                map.remove();
                map = null;
            }

            if (!data.length) {
                document.getElementById('attendance-map').innerHTML = `
                    <div class="flex items-center justify-center h-full min-h-[50vh] text-gray-400 dark:text-gray-500">
                        <div class="text-center px-6">
                            <svg class="w-16 h-16 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            <p class="text-base font-medium">Tidak ada data lokasi absensi</p>
                            <p class="text-sm mt-1">Untuk tanggal ini belum ada absensi dengan lokasi.</p>
                        </div>
                    </div>
                `;
                return;
            }

            map = L.map('attendance-map', {
                zoomControl: true,
                attributionControl: true,
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                maxZoom: 20,
            }).addTo(map);

            markerLayer = L.layerGroup().addTo(map);
            markers = [];

            data.forEach(function(attendance, index) {
                const name = attendance.user?.name || 'Unknown';
                const empId = attendance.user?.employee_id || '';
                const checkIn = attendance.check_in_time
                    ? new Date(attendance.check_in_time).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
                    : '-';
                const checkOut = attendance.check_out_time
                    ? new Date(attendance.check_out_time).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
                    : '-';

                const lat = parseFloat(attendance.check_in_latitude);
                const lng = parseFloat(attendance.check_in_longitude);
                if (isNaN(lat) || isNaN(lng)) return;

                const isOnTime = attendance.status === 'on_time';
                const color = isOnTime ? '#10B981' : '#EF4444';
                const borderColor = isOnTime ? '#059669' : '#DC2626';
                const statusIcon = isOnTime ? '✓' : '!';

                const icon = L.divIcon({
                    html: `<div style="
                        position:relative;
                        width:32px;height:32px;
                        background:${color};
                        border:3px solid ${borderColor};
                        border-radius:50%;
                        box-shadow:0 3px 10px rgba(0,0,0,0.25), 0 0 0 4px ${color}33;
                        display:flex;align-items:center;justify-content:center;
                        color:white;font-size:14px;font-weight:700;
                        transition:all 0.2s ease;
                    ">${statusIcon}</div>`,
                    iconSize: [32, 32],
                    iconAnchor: [16, 16],
                    popupAnchor: [0, -20],
                    className: 'custom-marker'
                });

                const marker = L.marker([lat, lng], { icon }).addTo(markerLayer);
                marker._index = index;
                markers.push(marker);

                marker.bindPopup(`
                    <div style="min-width:180px;">
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;padding-bottom:8px;border-bottom:1px solid #e5e7eb;">
                            <div style="
                                width:36px;height:36px;
                                border-radius:50%;
                                background:linear-gradient(135deg, #f59e0b, #d97706);
                                display:flex;align-items:center;justify-content:center;
                                color:white;font-weight:700;font-size:14px;
                                flex-shrink:0;
                            ">${name.charAt(0).toUpperCase()}</div>
                            <div>
                                <strong style="font-size:14px;color:#1f2937;">${name}</strong>
                                ${empId ? `<div style="font-size:11px;color:#6b7280;">${empId}</div>` : ''}
                            </div>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                            <div style="background:#f0fdf4;padding:6px 10px;border-radius:8px;text-align:center;">
                                <div style="font-size:10px;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px;">Check-in</div>
                                <div style="font-size:15px;font-weight:700;color:#059669;">${checkIn}</div>
                            </div>
                            <div style="background:#fef2f2;padding:6px 10px;border-radius:8px;text-align:center;">
                                <div style="font-size:10px;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px;">Check-out</div>
                                <div style="font-size:15px;font-weight:700;color:#dc2626;">${checkOut}</div>
                            </div>
                        </div>
                        <div style="margin-top:8px;text-align:center;">
                            <span style="
                                display:inline-block;
                                padding:2px 12px;
                                border-radius:999px;
                                font-size:11px;
                                font-weight:600;
                                ${isOnTime ? 'background:#d1fae5;color:#065f46;' : 'background:#fee2e2;color:#991b1b;'}
                            ">${isOnTime ? '✓ Tepat Waktu' : '✗ Terlambat'}</span>
                        </div>
                    </div>
                `, { maxWidth: 300, className: '' });
            });

            if (data.length > 0) {
                const group = L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.12), { maxZoom: 15 });
            }

            map.invalidateSize(true);
        };

        window.flyToMarker = function(lat, lng) {
            if (map) {
                map.flyTo([lat, lng], 17, { duration: 0.8 });

                markers.forEach(function(marker) {
                    if (marker.getLatLng().lat === lat && marker.getLatLng().lng === lng) {
                        marker.openPopup();
                        const el = marker.getElement();
                        if (el) {
                            el.style.transform += ' scale(1.4)';
                            setTimeout(() => { el.style.transform = el.style.transform.replace(' scale(1.4)', ''); }, 400);
                        }
                    }
                });
            }
        };

        document.addEventListener('highlight-marker', function(e) {
            const idx = e.detail;
            const marker = markers[idx];
            if (marker) {
                const el = marker.getElement();
                if (el) {
                    el.style.transform += ' scale(1.3)';
                    el.style.zIndex = '10000';
                }
            }
        });

        document.addEventListener('unhighlight-marker', function(e) {
            const idx = e.detail;
            const marker = markers[idx];
            if (marker) {
                const el = marker.getElement();
                if (el) {
                    el.style.transform = el.style.transform.replace(' scale(1.3)', '');
                    el.style.zIndex = '';
                }
            }
        });
    </script>
    @endpush
</x-filament-panels::page>
