@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <style>
        .map-ubicacion-wrap {
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(26, 92, 56, 0.12);
            border: 1px solid rgba(0, 0, 0, 0.08);
        }
        #map-picker-ubicacion {
            height: 300px;
            width: 100%;
            z-index: 1;
            background: #eef6f3;
        }
        .leaflet-container { font-family: inherit; }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mapEl = document.getElementById('map-picker-ubicacion');
            const latInput = document.getElementById('latitud');
            const lngInput = document.getElementById('longitud');
            if (!mapEl || !latInput || !lngInput || typeof L === 'undefined') {
                return;
            }

            const santaCruz = [-17.7833, -63.1821];
            const cityZoom = 12;

            function parseCoord(value) {
                if (value === null || value === undefined || String(value).trim() === '') {
                    return NaN;
                }
                return parseFloat(String(value).trim().replace(',', '.'));
            }

            let lat = parseCoord(latInput.value);
            let lng = parseCoord(lngInput.value);
            let center = santaCruz;
            let hasInitial = !Number.isNaN(lat) && !Number.isNaN(lng) && Math.abs(lat) <= 90 && Math.abs(lng) <= 180;
            if (hasInitial) {
                center = [lat, lng];
            }

            const map = L.map('map-picker-ubicacion', { scrollWheelZoom: true }).setView(center, hasInitial ? 14 : cityZoom);

            delete L.Icon.Default.prototype._getIconUrl;
            L.Icon.Default.mergeOptions({
                iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
                iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            }).addTo(map);

            let marker = null;
            if (hasInitial) {
                marker = L.marker(center).addTo(map);
            }

            function writeInputs(latlng) {
                latInput.value = latlng.lat.toFixed(7);
                lngInput.value = latlng.lng.toFixed(7);
            }

            map.on('click', function (e) {
                const ll = e.latlng;
                if (marker) {
                    marker.setLatLng(ll);
                } else {
                    marker = L.marker(ll).addTo(map);
                }
                writeInputs(ll);
            });

            function syncFromInputs() {
                lat = parseCoord(latInput.value);
                lng = parseCoord(lngInput.value);
                if (Number.isNaN(lat) || Number.isNaN(lng) || Math.abs(lat) > 90 || Math.abs(lng) > 180) {
                    return;
                }
                const ll = L.latLng(lat, lng);
                if (marker) {
                    marker.setLatLng(ll);
                } else {
                    marker = L.marker(ll).addTo(map);
                }
                map.setView(ll, Math.max(map.getZoom(), 13));
            }

            latInput.addEventListener('change', syncFromInputs);
            lngInput.addEventListener('change', syncFromInputs);

            requestAnimationFrame(function () {
                map.invalidateSize();
            });
        });
    </script>
@endpush
