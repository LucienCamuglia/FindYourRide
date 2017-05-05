/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var map;
var oldMarkers = [];
var Markers = [];
var oldRoute = [];
var route = [];
var highlighted;
var addedPoints = 1;


function initMap(modif, traffic) {
    modif = modif || false;
    traffic = traffic || false;
    var pos = {
        lat: 46.203545,
        lng: 6.145150
    };

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
        })
    }

    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 4,
        center: pos,
        mapTypeControl: true,
        mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
            mapTypeId: google.maps.MapTypeId.TERRAIN
        }
    });

    if (modif) {
        google.maps.event.addListener(map, 'click', function(event) {
            CreateMarker(event.latLng, true, true, "FE7569", ++addedPoints, addedPoints);
            $("#pointsInfo").append("<div id=point" + addedPoints + ">\n\
                                     <div class=\"Pointname col-lg-12\" >\n\
                                     <span class=\"col-lg-2 align-middle\" style=\"height: 100%;\" >Point  " + addedPoints + ": </span>\n\
                                     <div class=\"col-lg-10\">\n\
                                    <div> <label class=\"col-sm-3\">Latitude : </label> <input id='inpLatPoint" + addedPoints + "' type=\"text\" value=" + event.latLng.lat() + "> </div>\n\
                                    <div> <label class=\"col-sm-3\">Longitude : </label> <input id='inpLonPoint" + addedPoints + "' type=\"text\" value=" + event.latLng.lng() + ">\n\
                                       </div></div><div></div>   ");
        });
    }
    if (traffic) {
        var trafficLayer = new google.maps.TrafficLayer();
        trafficLayer.setMap(map);
    }
}

function ImporterGpx(file) {
    $.ajax({
        url: './Includes/ajax.php',
        type: 'GET',
        data: {fonction: "ImportGPX", file: file},
        dataType: "json",
        success: function(idRoute) {

        }
    });


}

function AskGoogle(Path) {
    var snapped = [];
    $.ajax({
        url: 'https://roads.googleapis.com/v1/snapToRoads',
        type: 'GET',
        data: {path: Path, interpolate: true, key: "AIzaSyCRxYbU0CGNMpZINbtBJqn72k1UCi0bMo8"},
        dataType: "json",
        async: false,
        success: function(r2) {

            $.each(r2["snappedPoints"], function(index, value)
            {
                snapped[index] = value.location;
                console.log(value.location);

            });
            console.log("exit");

        }
    });
    return snapped;
}

function LoadPoints(idRoute) {
    var resultArray = [];
    $.ajax({
        url: './Includes/ajax.php',
        type: 'GET',
        data: {fonction: "GetRoutePoints", idRoute: idRoute},
        dataType: "json",
        async: false,
        success: function(result) {
            $.each(result, function(index, value)
            {
                resultArray[index] = value;
            });
        }
    });
    return resultArray;
}

function SnappPoints2Road(route) {
    var Path = "";
    var tmpSnapped = [];
    var Snapped = [];
    $.each(route, function(index, point)
    {
        if ((index > 0) && (index % 50 === 0)) {
            Path = Path.slice(0, -1);
            tmpSnapped = AskGoogle(Path);
            Path = "";
            Snapped = Snapped.concat(tmpSnapped);

        }
        Path += point.Latitude + "," + point.Longitude + "|";
    });
    Path = Path.slice(0, -1);
    Snapped = Snapped.concat(AskGoogle(Path));
    return Snapped;
}

function SaveNewLocation(idroute, route) {
    $.ajax({
        url: './Includes/ajax.php',
        type: 'POST',
        data: {fonction: "SaveNewRoute", idRoute: idroute, route: route},
        async: false,
        success: function(result) {

        }
    });
}

$(document).ready(function() {
    initMap();
    clear();
    $(".route").click(function() {
        clear();
        initMap();
        $("#btnModif").removeClass("hidden");
        $("#btnModif").attr('name', $(this).attr('name'));
        if (highlighted != null)
            $(highlighted).removeClass("highlight");
        $(this).addClass("highlight");
        highlighted = this;

        ShowParcours(LoadPoints($(this).attr('name')), false);
    });
    $("#btnModif").click(function() {
        if (highlighted != null) {
            oldRoute = route;
            oldMarkers = Markers;
            clear();
            initMap(true);
            ShowParcours(LoadPoints($(this).attr('name')), true);
            $(".modifRoute").removeClass("hidden");
            $("#inpLatPoint0").val(Markers[0].position.lat());
            $("#inpLonPoint0").val(Markers[0].position.lng());
            $("#inpLatPoint1").val(Markers[1].position.lat());
            $("#inpLonPoint1").val(Markers[1].position.lng());
        }
    });
    $("#RefreshRoute").click(function() {
        console.log("Refresh");
        if (oldMarkers.length == Markers.length) {
            console.log("old route");
            console.log(route);
            route = [];
            for (var i = 0; i < Markers.length; i++) {
                var from = [];
                from["lat"] = Markers[i].position.lat();
                from["lon"] = Markers[i].position.lng();
                var to = [];
                to["lat"] = oldMarkers[i].position.lat();
                to["lon"] = oldMarkers[i].position.lng();

                route.push(searchIti(from, to));

            }

            oldRoute.shift()
            oldRoute.unshift(route[0][0], route[0][1]);
            //  oldRoute[oldRoute.length-1] = route[1];
            route = oldRoute;
            DisplayRoute();
            console.log("new route");
            console.log(route);
        }
    });
});

function searchIti(from, to) {
    /*  console.log("from :");
     console.log("   lat : " + from["lat"]);
     console.log("   lon : " + from["lon"]);
     console.log("to :");
     console.log("   lat : " + to["lat"]);
     console.log("   lon : " + to["lon"]);*/
    var routeResp = [];
    $.ajax({
        url: 'https://maps.googleapis.com/maps/api/directions/json',
        type: 'GET',
        crossDomain: true,
        data: {origin: from["lat"] + "," + from["lon"], destination: to["lat"] + "," + to["lon"], key: "AIzaSyCRxYbU0CGNMpZINbtBJqn72k1UCi0bMo8"},
        async: false,
        dataType: 'json',
        success: function(result) {
            //  console.log(result);
            $.each(result.routes[0].legs[0].steps, function(index, value)
            {
                routeResp.push(new google.maps.LatLng(value.start_location));
                routeResp.push(new google.maps.LatLng(value.end_location));
            });
        }
    });
    return routeResp;
}

function clear() {
    Markers = [];
    route = [];
}

/* Affiche les points
 @param array tableauPoints : Tableau des marqueurs Ã  afficher sur la map
 @param bool parcoursModif : savoir si les marqueurs route
 est modifiable ou pas(dÃ©plaÃ§able, supprimable)
 */
function ShowParcours(tableauPoints, parcoursModif, color) {
    color = color || "#ff1ece";
    var liste_des_points = tableauPoints;


    $.each(liste_des_points, function(index, value)
    {
        route.push(new google.maps.LatLng(value.Latitude, value.Longitude));
    });

    //crée les marqueurs de début et fin de route
    CreateMarker(liste_des_points[0], parcoursModif, false, "36af2d", "%E2%80%A2", 0);
    CreateMarker(liste_des_points[liste_des_points.length - 1], parcoursModif, false, "a52424", "%E2%80%A2", 1);

    var bounds = new google.maps.LatLngBounds();

    if (Markers.length == 0) {
        //Genève
        bounds.extend({lat: 46.2, lng: 6.1667});
        bounds.extend({lat: 46.2, lng: 6.12});
    }
    for (var i = 0; i < Markers.length; i++) {
        bounds.extend(Markers[i].getPosition());
    }
    map.fitBounds(bounds);
    DisplayRoute(color);
}

function DisplayRoute(color) {
    color = color || "#ff1ece";
    var parcoursPolyline = new google.maps.Polyline({
        path: route,
        strokeColor: color,
        strokeOpacity: 1.0,
        strokeWeight: 5,
        map: map
    });

}

function CreateMarker(value, parcoursModif, parcoursDelete, pinColor, texte, position) {
    var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=" + texte + "|" + pinColor,
            new google.maps.Size(21, 34),
            new google.maps.Point(0, 0),
            new google.maps.Point(10, 34));

    var pos;

    if (value.Latitude) {
        pos = new google.maps.LatLng(value.Latitude, value.Longitude);
    } else {
        pos = value;
    }
    //nouveau marker
    parcoursMarker = new google.maps.Marker({
        position: pos,
        draggable: parcoursModif,
        zIndex: position,
        map: map,
        icon: pinImage
    });

    if (parcoursDelete) {
        parcoursMarker.addListener('click', function() {
            this.setMap(null);
            Markers.splice(Markers.indexOf(this), 1);
            $("#point" + this.zIndex).remove();
            length--;
        });
    }

    if (parcoursModif) {
        parcoursMarker.addListener('dragend', function() {
            refreshValues(this.zIndex);
            Markers[this.zIndex].previous = Markers[this.zIndex].position;
            Markers[this.zIndex].latLng = this.position;
        });
    }

    //  Ajout du marqueur dans le tableau
    Markers.push(parcoursMarker);
}

function refreshValues(index) {
    console.log("Display values");
    $("#inpLatPoint" + index).val(Markers[index].position.lat());
    $("#inpLonPoint" + index).val(Markers[index].position.lng());

}