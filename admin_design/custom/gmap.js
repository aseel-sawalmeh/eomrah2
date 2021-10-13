function get_cities(cid) {
    if (cid.length == 0) {
        document.getElementById("cities").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("cities").innerHTML = this.responseText;
            }
        };
    }
    xmlhttp.open(
        "GET", "/chotel/provider/aj_country_cities/" +cid,
        true
    );
    xmlhttp.send();
}

function get_regions(gid) {
    if (gid.length == 0) {
        document.getElementById("regions").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById(
                    "regions"
                ).innerHTML = this.responseText;
            }
        };
    }
    xmlhttp.open(
        "GET", "/chotel/provider/aj_country_regions/" + gid,
        true
    );
    xmlhttp.send();
}

function initMap() {
    var map = new google.maps.Map(document.getElementById("map"), {
        center: {
            lat: 22.3038945,
            lng: 70.80215989999999
        },

        zoom: 13
    });

    var input = document.getElementById("searchMapInput");

    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    var autocomplete = new google.maps.places.Autocomplete(input);

    autocomplete.bindTo("bounds", map);

    var infowindow = new google.maps.InfoWindow();

    var marker = new google.maps.Marker({
        map: map,
        animation: google.maps.Animation.DROP,
        anchorPoint: new google.maps.Point(0, -29),
        draggable: true
    });

    google.maps.event.addListener(marker, "dragend", function(marker) {
        var latLng = marker.latLng;

        document.getElementById("lat-span").innerHTML = latLng.lat();

        document.getElementById("lon-span").innerHTML = latLng.lng();
    });

    autocomplete.addListener("place_changed", function() {
        infowindow.close();

        marker.setVisible(false);

        var place = autocomplete.getPlace();

        /* If the place has a geometry, then present it on a map. */

        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);

            map.setZoom(17);
        }

        marker.setIcon({
            url: place.icon,

            size: new google.maps.Size(71, 71),

            origin: new google.maps.Point(0, 0),

            anchor: new google.maps.Point(17, 34),

            scaledSize: new google.maps.Size(35, 35)
        });

        marker.setPosition(place.geometry.location);

        marker.setVisible(true);

        var address = "";

        if (place.address_components) {
            address = [
                (place.address_components[0] &&
                    place.address_components[0].short_name) ||
                    "",
                (place.address_components[1] &&
                    place.address_components[1].short_name) ||
                    "",
                (place.address_components[2] &&
                    place.address_components[2].short_name) ||
                    ""
            ].join(" ");
        }

        infowindow.setContent(
            "<div><strong>" + place.name + "</strong><br>" + address
        );

        infowindow.open(map, marker);

        /* Location details */

        document.getElementById("location-snap").innerHTML =
            place.formatted_address;
        document.getElementById("address-snap").value = place.formatted_address;

        document.getElementById(
            "lat-span"
        ).innerHTML = place.geometry.location.lat();

        document.getElementById(
            "lon-span"
        ).innerHTML = place.geometry.location.lng();
    });
}
