<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        x-data="mapPickerComponent($wire, '{{ $getStatePath('latitude') }}', '{{ $getStatePath('longitude') }}')"
        x-init="init()"
        wire:ignore
        class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700"
        style="position: relative;"
    >
        <div x-ref="map" style="height: 400px;"></div>

        <button
            type="button"
            @click="getCurrentLocation()"
            class="absolute bottom-4 right-4 z-[1000] bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 px-3 py-2 rounded-lg shadow-md text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors flex items-center gap-2"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Use Current Location
        </button>
    </div>
</x-dynamic-component>

<script>
function mapPickerComponent($wire, latPath, lngPath) {
    return {
        map: null,
        marker: null,
        initialized: false,
        init() {
            if (this.initialized) return
            this.initialized = true

            if (typeof L === 'undefined') {
                const script = document.createElement('script')
                script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js'
                script.onload = () => this.initMap()
                document.head.appendChild(script)

                const link = document.createElement('link')
                link.rel = 'stylesheet'
                link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css'
                document.head.appendChild(link)
            } else {
                this.initMap()
            }
        },
        initMap() {
            const container = this.$refs.map
            if (!container) return

            const defaultLat = parseFloat($wire.get(latPath)) || -6.200000
            const defaultLng = parseFloat($wire.get(lngPath)) || 106.845000

            delete L.Icon.Default.prototype._getIconUrl
            L.Icon.Default.mergeOptions({
                iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
                iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png'
            })

            this.map = L.map(container, { zoomControl: false }).setView([defaultLat, defaultLng], 13)
            L.control.zoom({ position: 'topright' }).addTo(this.map)

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(this.map)

            if ($wire.get(latPath) && $wire.get(lngPath)) {
                this.marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(this.map)
            }

            this.map.on('click', (e) => {
                const { lat, lng } = e.latlng
                this.updateInputs(lat, lng)

                if (this.marker) {
                    this.marker.setLatLng([lat, lng])
                } else {
                    this.marker = L.marker([lat, lng], { draggable: true }).addTo(this.map)
                }

                this.marker.on('dragend', () => {
                    const pos = this.marker.getLatLng()
                    this.updateInputs(pos.lat, pos.lng)
                })
            })

            setTimeout(() => this.map.invalidateSize(), 200)
        },
        updateInputs(lat, lng) {
            $wire.set(latPath, lat.toFixed(8))
            $wire.set(lngPath, lng.toFixed(8))
        },
        getCurrentLocation() {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser')
                return
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude
                    const lng = position.coords.longitude
                    this.map.setView([lat, lng], 16)

                    if (this.marker) {
                        this.marker.setLatLng([lat, lng])
                    } else {
                        this.marker = L.marker([lat, lng], { draggable: true }).addTo(this.map)
                    }

                    this.updateInputs(lat, lng)
                },
                (error) => {
                    alert('Unable to get your location: ' + error.message)
                }
            )
        }
    }
}
</script>
