<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    @pushonce('leaflet-css')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endpushonce

    <div
        x-data="{
            state: $wire.$entangle('{{ $getStatePath() }}'),
            lat: $wire.$entangle('{{ $getStatePath('latitude') }}'),
            lng: $wire.$entangle('{{ $getStatePath('longitude') }}'),
            map: null,
            marker: null,
            leafletLoaded: false,
            init() {
                if (typeof L !== 'undefined') {
                    this.leafletLoaded = true
                    this.initMap()
                } else {
                    const script = document.createElement('script')
                    script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js'
                    script.onload = () => {
                        this.leafletLoaded = true
                        this.initMap()
                    }
                    document.head.appendChild(script)
                }
            },
            initMap() {
                const defaultLat = this.lat || -6.200000
                const defaultLng = this.lng || 106.845000

                this.map = L.map($refs.map).setView([defaultLat, defaultLng], 13)

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
                    this.state = `${lat}, ${lng}`

                    if (this.marker) {
                        this.marker.setLatLng([lat, lng])
                    } else {
                        this.marker = L.marker([lat, lng], { draggable: true }).addTo(this.map)
                    }

                    this.marker.on('dragend', () => {
                        const pos = this.marker.getLatLng()
                        this.lat = pos.lat
                        this.lng = pos.lng
                        this.state = `${pos.lat}, ${pos.lng}`
                    })
                })

                setTimeout(() => this.map.invalidateSize(), 100)
            }
        }"
        x-init="init()"
        wire:ignore
        class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700"
    >
        <div x-ref="map" class="h-96 w-full"></div>
    </div>
</x-dynamic-component>
