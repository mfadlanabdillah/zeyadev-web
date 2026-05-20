<?php
    /** @var \App\Filament\Pages\AttendanceMap $this */
?>

<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 px-6 py-4">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Peta Absensi
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ \Carbon\Carbon::parse($filterDate)->locale('id')->translatedFormat('l, d F Y') }}
                    </p>
                </div>
            </div>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <input type="date" wire:model.live="filterDate" value="{{ $filterDate }}"
                       class="block rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm pl-10 pr-4 py-2 focus:border-primary-500 focus:ring-primary-500">
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 shrink-0">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Absensi</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ count($markers) }}</p>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 shrink-0">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tepat Waktu</p>
                    <p class="mt-1 text-2xl font-bold text-green-600 dark:text-green-400">{{ $onTimeCount }}</p>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 shrink-0">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Terlambat</p>
                    <p class="mt-1 text-2xl font-bold text-red-600 dark:text-red-400">{{ $lateCount }}</p>
                </div>
            </div>
        </div>

        {{-- Map --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 px-5 py-3">
                <div class="flex items-center gap-2">
                    <span class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                        <span class="inline-block h-3 w-3 rounded-full bg-green-500"></span> Tepat Waktu
                    </span>
                    <span class="text-gray-300 dark:text-gray-600">|</span>
                    <span class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                        <span class="inline-block h-3 w-3 rounded-full bg-red-500"></span> Terlambat
                    </span>
                </div>
                <span class="text-xs text-gray-400 dark:text-gray-500">
                    {{ count($markers) }} lokasi
                </span>
            </div>
            <div
                id="attendance-map"
                class="w-full"
                style="height: 520px;"
                wire:key="map-{{ $filterDate }}"
                x-data="attendanceMap(@js($markers))"
                x-init="init()"
            ></div>
        </div>

        {{-- Table --}}
        @if (count($markers) > 0)
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
                <div class="border-b border-gray-200 dark:border-gray-700 px-5 py-3 flex items-center justify-between">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Daftar Absensi</p>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ count($markers) }} karyawan</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-700/30">
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Karyawan</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Check-in</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Check-out</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                            @foreach($markers as $index => $attendance)
                                <tr wire:key="{{ $index }}"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-700/30 cursor-pointer transition-colors"
                                    onclick="flyToMarker({{ $attendance['check_in_latitude'] }}, {{ $attendance['check_in_longitude'] }})">
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 text-xs font-bold">
                                                {{ strtoupper(substr($attendance['user']['name'] ?? '?', 0, 1)) }}
                                            </div>
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $attendance['user']['name'] ?? 'Unknown' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $attendance['check_in_time'] ? \Carbon\Carbon::parse($attendance['check_in_time'])->format('H:i') : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $attendance['check_out_time'] ? \Carbon\Carbon::parse($attendance['check_out_time'])->format('H:i') : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        @if($attendance['status'] === 'on_time')
                                            <span class="inline-flex items-center gap-1 rounded-full bg-green-50 dark:bg-green-900/20 px-3 py-1 text-xs font-semibold text-green-700 dark:text-green-400">
                                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                                Tepat Waktu
                                            </span>
                                        @elseif($attendance['status'] === 'late')
                                            <span class="inline-flex items-center gap-1 rounded-full bg-red-50 dark:bg-red-900/20 px-3 py-1 text-xs font-semibold text-red-700 dark:text-red-400">
                                                <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                                Terlambat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-800 px-3 py-1 text-xs font-semibold text-gray-600 dark:text-gray-400">{{ $attendance['status'] }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <svg class="h-16 w-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                    <p class="text-lg font-medium text-gray-500 dark:text-gray-400">Belum ada data absensi</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Tidak ada catatan absensi untuk tanggal ini</p>
                </div>
            </div>
        @endif
    </div>

    <style>
        #attendance-map .leaflet-popup-content { margin: 12px 16px; font-size: 13px; line-height: 1.6; }
        #attendance-map .leaflet-popup-content strong { display: block; font-size: 14px; margin-bottom: 4px; }
        .dark #attendance-map .leaflet-popup-content-wrapper { background: #1f2937; color: #d1d5db; }
        .dark #attendance-map .leaflet-popup-tip { background: #1f2937; }
    </style>

    <script>
    function attendanceMap(data) {
        return {
            map: null,
            markers: [],
            init() {
                if (typeof L === 'undefined') return

                const el = document.getElementById('attendance-map')
                if (!el) return

                if (!data || !data.length) {
                    el.innerHTML = '<div class="flex items-center justify-center h-full text-gray-400"><p>Belum ada data untuk tanggal ini</p></div>'
                    return
                }

                this.map = L.map('attendance-map').setView([-6.2, 106.82], 12)
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(this.map)
                this.markers = []

                data.forEach(function(attendance) {
                    const lat = parseFloat(attendance.check_in_latitude)
                    const lng = parseFloat(attendance.check_in_longitude)
                    if (isNaN(lat) || isNaN(lng)) return
                    const isOn = attendance.status === 'on_time'
                    const color = isOn ? '#10B981' : '#EF4444'

                    const icon = L.divIcon({
                        html: `<div style="width:24px;height:24px;background:${color};border:3px solid white;border-radius:50%;box-shadow:0 2px 8px rgba(0,0,0,0.3);"></div>`,
                        iconSize: [24, 24], iconAnchor: [12, 12], popupAnchor: [0, -16], className: ''
                    })

                    const marker = L.marker([lat, lng], { icon }).addTo(this.map)
                    this.markers.push(marker)
                    marker.bindPopup(`<strong>${attendance.user?.name || ''}</strong><br>Check-in: ${attendance.check_in_time ? new Date(attendance.check_in_time).toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'}) : '-'}<br>${isOn ? 'Tepat Waktu' : 'Terlambat'}`)
                }.bind(this))

                if (this.markers.length) {
                    const group = L.featureGroup(this.markers)
                    this.map.fitBounds(group.getBounds().pad(0.1))
                }
                this.map.invalidateSize()
            }
        }
    }

    function flyToMarker(lat, lng) {
        const el = document.getElementById('attendance-map')
        if (!el) return
        const alpine = Alpine.$data(el)
        if (alpine.map) {
            alpine.map.flyTo([lat, lng], 17, { duration: 0.8 })
            alpine.markers.forEach(m => { if (m.getLatLng().lat === lat && m.getLatLng().lng === lng) m.openPopup() })
        }
    }
    </script>
</x-filament-panels::page>
