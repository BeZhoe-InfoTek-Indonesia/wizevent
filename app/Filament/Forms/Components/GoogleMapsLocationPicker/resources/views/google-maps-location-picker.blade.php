@props([
    'getStatePath',
    'getMapHeight',
    'getMapType',
    'getZoom',
    'getShowMap',
])

<div 
    x-data="mapsPicker({
        id: '{{ $getId() }}',
        mapId: 'map-{{ $getId() }}',
        height: '{{ $getMapHeight() }}',
        zoom: {{ $getZoom() }},
        type: '{{ $getMapType() }}',
        showMap: {{ $getShowMap() ? 'true' : 'false' }},
        apiKey: '{{ env('GOOGLE_MAPS_API_KEY', '') }}',
        latitudePath: '{{ $getStatePath() }}.latitude',
        longitudePath: '{{ $getStatePath() }}.longitude',
        placeIdPath: '{{ $getStatePath() }}.google_place_id',
    })"
    x-init="init()"
    class="space-y-3"
>
    <div class="relative">
        <input 
            type="text"
            id="{{ $getId() }}"
            {{ $attributes->merge(['class' => 'w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm']) }}
            x-model="location"
            x-ref="locationInput"
            placeholder="{{ $getPlaceholder() }}"
        />
        @if($getShowMap())
        <button 
            type="button"
            @click="useCurrentLocation()"
            class="absolute right-2 top-1/2 -translate-y-1/2 p-1 text-gray-400 hover:text-gray-600 transition"
            title="Use current location"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </button>
        @endif
    </div>
    
    @if($getShowMap())
    <div 
        id="map-{{ $getId() }}" 
        class="rounded-md border border-gray-300 bg-gray-100"
        style="height: {{ $getMapHeight() }};">
    </div>
    @endif
</div>

@push('scripts')
<script>
window.mapsPicker = function(options) {
    return {
        location: '',
        latitude: '',
        longitude: '',
        placeId: '',
        map: null,
        marker: null,
        autocomplete: null,
        
        init() {
            this.$watch('$wire.' + options.latitudePath, (val) => {
                if (val) this.latitude = val;
            });
            
            this.$watch('$wire.' + options.longitudePath, (val) => {
                if (val) this.longitude = val;
            });
            
            this.$watch('$wire.' + options.placeIdPath, (val) => {
                if (val) this.placeId = val;
            });
            
            if (options.apiKey && typeof google === 'undefined') {
                const script = document.createElement('script');
                script.src = `https://maps.googleapis.com/maps/api/js?key=${options.apiKey}&libraries=places`;
                script.async = true;
                script.defer = true;
                script.onload = () => this.onGoogleLoaded();
                document.head.appendChild(script);
            } else if (typeof google !== 'undefined') {
                this.onGoogleLoaded();
            }
        },
        
        onGoogleLoaded() {
            if (options.showMap) {
                const mapOptions = {
                    zoom: options.zoom,
                    mapTypeId: options.type,
                    center: { lat: -6.2088, lng: 106.8456 }, // Default to Jakarta
                };
                
                const mapElement = document.getElementById(options.mapId);
                if (mapElement) {
                    this.map = new google.maps.Map(mapElement, mapOptions);
                    this.marker = new google.maps.Marker({
                        map: this.map,
                        draggable: true,
                    });
                    
                    this.marker.addListener('dragend', (e) => {
                        this.latitude = e.latLng.lat();
                        this.longitude = e.latLng.lng();
                        this.updateModel();
                    });
                }
            }
            
            this.initAutocomplete();
        },
        
        initAutocomplete() {
            const input = document.getElementById(options.id);
            if (!input || !google) return;
            
            this.autocomplete = new google.maps.places.Autocomplete(input, {
                fields: ['formatted_address', 'geometry', 'name', 'place_id'],
            });
            
            this.autocomplete.addListener('place_changed', () => {
                const place = this.autocomplete.getPlace();
                
                if (!place.geometry || !place.geometry.location) {
                    alert('No location found for the entered place');
                    return;
                }
                
                this.location = place.formatted_address || place.name;
                this.placeId = place.place_id;
                this.latitude = place.geometry.location.lat();
                this.longitude = place.geometry.location.lng();
                
                if (this.map) {
                    this.map.setCenter(place.geometry.location);
                    this.marker.setPosition(place.geometry.location);
                }
                
                this.updateModel();
            });
        },
        
        useCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;
                        
                        this.latitude = lat;
                        this.longitude = lng;
                        
                        if (this.map) {
                            const position = { lat, lng };
                            this.map.setCenter(position);
                            this.marker.setPosition(position);
                            
                            // Reverse geocode
                            const geocoder = new google.maps.Geocoder();
                            geocoder.geocode({ location: position }, (results, status) => {
                                if (status === 'OK' && results[0]) {
                                    this.location = results[0].formatted_address;
                                }
                            });
                        }
                        
                        this.updateModel();
                    },
                    (err) => {
                        console.error(err);
                        alert('Unable to get your location. Please enable location services.');
                    }
                );
            }
        },
        
        updateModel() {
            this.$wire.set(options.latitudePath, this.latitude);
            this.$wire.set(options.longitudePath, this.longitude);
            this.$wire.set(options.placeIdPath, this.placeId);
        },
    };
};
</script>
@endpush
