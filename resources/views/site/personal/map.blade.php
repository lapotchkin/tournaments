@extends('layouts.site')

@section('title', 'Карта игроков: ' . $tournament->title . ' — ')

@section('content')
    {{ Breadcrumbs::render('personal.tournament.map', $tournament) }}
    @widget('personalHeader', ['tournament' => $tournament])
    @widget('personalMenu', ['tournament' => $tournament])

    <div id="map" class="mt-4 w-100"></div>
@endsection

@section('script')
    @parent
    <style>
        #map {
            height: 600px;
            border: 1px solid #ccc;
        }
    </style>

    <script type="text/javascript">
        $(document).ready(function () {
            const addressPoints = {!! json_encode($points) !!};
            const tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Points &copy 2012 LINZ'
            });
            const map = L.map('map', {layers: [tiles]});

            map.fitBounds(addressPoints);

            const markers = L.markerClusterGroup();
            // var markers = L.layerGroup();

            for (let i = 0; i < addressPoints.length; i++) {
                const icon = L.icon({
                    iconUrl: '/images/pic/icehockey-2.png',
                    iconAnchor:   [16, 37], // point of the icon which will correspond to marker's location
                });
                const a = addressPoints[i];
                const title = a[2];
                const marker = L.marker(new L.LatLng(a[0], a[1]), {title: title, icon: icon});
                marker.bindPopup(title);
                markers.addLayer(marker);
            }

            map.addLayer(markers);
        });
    </script>
@endsection
