<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <style>
        .leaflet-map-container { position: relative; overflow: hidden; }
        .leaflet-map-container .leaflet-container { position: relative !important; }
    </style>

    <div
        x-data="{
            state: @entangle($getStatePath()),
            lat: @entangle($getStatePath('latitude')),
            lng: @entangle($getStatePath('longitude')),
            map: null,
            marker: null,
            initialized: false,
            init() {
                if (this.initialized) return
                this.initialized = true

                const defaultLat = this.lat || -6.200000
                const defaultLng = this.lng || 106.845000

                delete L.Icon.Default.prototype._getIconUrl
                L.Icon.Default.mergeOptions({
                    iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
                    iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png'
                })

                this.map = L.map(this.$refs.map, { zoomControl: false }).setView([defaultLat, defaultLng], 13)
                L.control.zoom({ position: 'topright' }).addTo(this.map)

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(this.map)

                if (this.lat && this.lng) {
                    this.marker = L.marker([this.lat, this.lng], { draggable: true }).addTo(this.map)
                }

                this.map.on('click', (e) => {
                    const { lat, lng } = e.latlng
                    this.lat = lat
                    this.lng = lng
                    this.state = lat + ', ' + lng

                    if (this.marker) {
                        this.marker.setLatLng([lat, lng])
                    } else {
                        this.marker = L.marker([lat, lng], { draggable: true }).addTo(this.map)
                    }

                    this.marker.on('dragend', () => {
                        const pos = this.marker.getLatLng()
                        this.lat = pos.lat
                        this.lng = pos.lng
                        this.state = pos.lat + ', ' + pos.lng
                    })
                })

                setTimeout(() => this.map.invalidateSize(), 200)
            }
        }"
        x-init="init()"
        wire:ignore
        class="leaflet-map-container rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700"
    >
        <div x-ref="map" class="h-96 w-full"></div>
    </div>
</x-dynamic-component>
