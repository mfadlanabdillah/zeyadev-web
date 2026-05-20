<x-filament-panels::page>
    <style>
        #attendance-map { height: 600px; border-radius: 8px; }
        .leaflet-popup-content { margin: 10px; min-width: 200px; }
        .map-popup { font-size: 13px; }
        .map-popup strong { display: inline-block; min-width: 80px; }
    </style>

    @php
        $attendances = $this->getAttendances();
        $branch = $attendances->first()?->branch;
        $branchLat = $branch?->latitude ?? -6.200000;
        $branchLng = $branch?->longitude ?? 106.845000;
    @endphp

    <div wire:ignore>
        <div id="attendance-map"></div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof L === 'undefined') return

        const map = L.map('attendance-map').setView([{{ $branchLat }}, {{ $branchLng }}], 13)

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map)

        // Branch marker
        L.circleMarker([{{ $branchLat }}, {{ $branchLng }}], {
            radius: 20,
            color: '#f59e0b',
            fillColor: '#f59e0b',
            fillOpacity: 0.2,
            weight: 2,
        }).addTo(map).bindPopup('<b>Branch Location</b>')

        @foreach ($attendances as $attendance)
            @if ($attendance->check_in_latitude && $attendance->check_in_longitude)
                L.marker([{{ $attendance->check_in_latitude }}, {{ $attendance->check_in_longitude }}], {
                    icon: L.divIcon({
                        className: '',
                        html: '<div style="background:#22c55e;width:16px;height:16px;border-radius:50%;border:2px solid white;box-shadow:0 1px 3px rgba(0,0,0,0.3);"></div>',
                        iconSize: [16, 16],
                        iconAnchor: [8, 8],
                    })
                }).addTo(map).bindPopup(`
                    <div class="map-popup">
                        <b>Check In</b><br>
                        <strong>User:</strong> {{ $attendance->user?->name }}<br>
                        <strong>Date:</strong> {{ $attendance->attendance_date }}<br>
                        <strong>Time:</strong> {{ $attendance->check_in_time?->format('H:i:s') }}<br>
                        <strong>Status:</strong> {{ $attendance->status }}
                    </div>
                `)
            @endif

            @if ($attendance->check_out_latitude && $attendance->check_out_longitude)
                L.marker([{{ $attendance->check_out_latitude }}, {{ $attendance->check_out_longitude }}], {
                    icon: L.divIcon({
                        className: '',
                        html: '<div style="background:#ef4444;width:16px;height:16px;border-radius:50%;border:2px solid white;box-shadow:0 1px 3px rgba(0,0,0,0.3);"></div>',
                        iconSize: [16, 16],
                        iconAnchor: [8, 8],
                    })
                }).addTo(map).bindPopup(`
                    <div class="map-popup">
                        <b>Check Out</b><br>
                        <strong>User:</strong> {{ $attendance->user?->name }}<br>
                        <strong>Date:</strong> {{ $attendance->attendance_date }}<br>
                        <strong>Time:</strong> {{ $attendance->check_out_time?->format('H:i:s') }}
                    </div>
                `)
            @endif
        @endforeach

        setTimeout(() => map.invalidateSize(), 200)
    })
    </script>
</x-filament-panels::page>
