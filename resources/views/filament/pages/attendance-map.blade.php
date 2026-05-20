<?php
    /** @var \App\Filament\Pages\AttendanceMap $this */
?>

<x-filament-panels::page>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Peta Absensi
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ \Carbon\Carbon::parse($filterDate)->locale('id')->translatedFormat('l, d F Y') }}
                </p>
            </div>
            <input type="date" wire:model.live="filterDate" value="{{ $filterDate }}"
                   class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm text-sm">
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total</p>
                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ count($markers) }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Tepat Waktu</p>
                <p class="mt-1 text-2xl font-bold text-green-600 dark:text-green-400">{{ $onTimeCount }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Terlambat</p>
                <p class="mt-1 text-2xl font-bold text-red-600 dark:text-red-400">{{ $lateCount }}</p>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden">
            <div
                id="attendance-map"
                class="w-full"
                style="height: 480px;"
                wire:key="map-{{ $filterDate }}"
                x-data="attendanceMap(@js($markers))"
                x-init="init()"
            ></div>
        </div>

        @if (count($markers) > 0)
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden">
                <div class="border-b border-gray-200 dark:border-gray-700 px-5 py-3">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Daftar Absensi</p>
                </div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/30">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Karyawan</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Check-in</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Check-out</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @foreach($markers as $index => $attendance)
                            <tr wire:key="{{ $index }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/30 cursor-pointer" onclick="flyToMarker({{ $attendance['check_in_latitude'] }}, {{ $attendance['check_in_longitude'] }})">
                                <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">{{ $attendance['user']['name'] ?? 'Unknown' }}</td>
                                <td class="px-5 py-3 text-gray-700 dark:text-gray-300">{{ $attendance['check_in_time'] ? \Carbon\Carbon::parse($attendance['check_in_time'])->format('H:i') : '-' }}</td>
                                <td class="px-5 py-3 text-gray-500 dark:text-gray-400">{{ $attendance['check_out_time'] ? \Carbon\Carbon::parse($attendance['check_out_time'])->format('H:i') : '-' }}</td>
                                <td class="px-5 py-3">
                                    @if($attendance['status'] === 'on_time')
                                        <span class="inline-flex items-center rounded-full bg-green-50 dark:bg-green-900/20 px-3 py-1 text-xs font-semibold text-green-700 dark:text-green-400">Tepat Waktu</span>
                                    @elseif($attendance['status'] === 'late')
                                        <span class="inline-flex items-center rounded-full bg-red-50 dark:bg-red-900/20 px-3 py-1 text-xs font-semibold text-red-700 dark:text-red-400">Terlambat</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-800 px-3 py-1 text-xs font-semibold text-gray-600 dark:text-gray-400">{{ $attendance['status'] }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                    marker.bindPopup(`<strong>${attendance.user?.name || ''}</strong><br>Check-in: ${attendance.check_in_time ? new Date(attendance.check_in_time).toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'}) : '-'}<br>${isOn ? '✅ Tepat Waktu' : '⚠️ Terlambat'}`)
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
