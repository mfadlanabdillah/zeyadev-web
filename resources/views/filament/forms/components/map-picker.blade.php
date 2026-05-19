<div
    x-data="mapPicker()"
    x-init="init($refs.map)"
    wire:ignore
    class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700"
    style="position: relative;"
>
    <div x-ref="map" style="height: 400px;"></div>
</div>

<script>
function mapPicker() {
    return {
        map: null,
        marker: null,
        initialized: false,
        init(container) {
            if (this.initialized || typeof L === 'undefined') return
            this.initialized = true

            const latInput = document.querySelector('[data-field-name="latitude"] input')
            const lngInput = document.querySelector('[data-field-name="longitude"] input')

            const defaultLat = latInput ? parseFloat(latInput.value) || -6.200000 : -6.200000
            const defaultLng = lngInput ? parseFloat(lngInput.value) || 106.845000 : 106.845000

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

            if (latInput && lngInput && latInput.value && lngInput.value) {
                this.marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(this.map)
            }

            this.map.on('click', (e) => {
                const { lat, lng } = e.latlng
                if (latInput) latInput.value = lat
                if (lngInput) lngInput.value = lng
                latInput?.dispatchEvent(new Event('input', { bubbles: true }))
                lngInput?.dispatchEvent(new Event('input', { bubbles: true }))

                if (this.marker) {
                    this.marker.setLatLng([lat, lng])
                } else {
                    this.marker = L.marker([lat, lng], { draggable: true }).addTo(this.map)
                }

                this.marker.on('dragend', () => {
                    const pos = this.marker.getLatLng()
                    if (latInput) latInput.value = pos.lat
                    if (lngInput) lngInput.value = pos.lng
                    latInput?.dispatchEvent(new Event('input', { bubbles: true }))
                    lngInput?.dispatchEvent(new Event('input', { bubbles: true }))
                })
            })

            setTimeout(() => this.map.invalidateSize(), 200)
        }
    }
}
</script>
