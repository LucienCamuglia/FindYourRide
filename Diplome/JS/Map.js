/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//Google API KEYS, DEFAULT is my default key for this project ans RESCUE is used only if DEFAULT'S quotas has been reached
var KEY = (function() {
     var private = {
         'DEFAULT': 'AIzaSyCRxYbU0CGNMpZINbtBJqn72k1UCi0bMo8',
         'RESCUE': 'AIzaSyAe7EU4muOht_cbrvH88JTd2FTrvbsYZ_E'
     };
     return {
        get: function(name) { return private[name]; }
    };
})();


var map;
var mapkey = KEY.get('RESCUE');
var polyline;
var pointsArray = [];
var midmarkers = [];
var oldMarkers = [];
var Markers = [];
var oldRoute = [];
var route = [];
var highlighted;
var addedPoints = 1;
var directionsDisplay = new google.maps.DirectionsRenderer({suppressMarkers: true});
var directionsService = new google.maps.DirectionsService();
var elevator = new google.maps.ElevationService;
var parcoursPolyline;
var imageNormal = new google.maps.MarkerImage(
        "./images/lines/square.png",
        new google.maps.Size(11, 11),
        new google.maps.Point(0, 0),
        new google.maps.Point(6, 6)
        );
var imageHover = new google.maps.MarkerImage(
        "./images/lines/square_over.png",
        new google.maps.Size(11, 11),
        new google.maps.Point(0, 0),
        new google.maps.Point(6, 6)
        );
var imageNormalMidpoint = new google.maps.MarkerImage(
        "./images/lines/square_transparent.png",
        new google.maps.Size(11, 11),
        new google.maps.Point(0, 0),
        new google.maps.Point(6, 6)
        );
var total = 0;

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
        data: {path: Path, interpolate: true, key: mapkey},
        dataType: "json",
        async: false,
        success: function(r2) {

            $.each(r2["snappedPoints"], function(index, value)
            {
                snapped[index] = value.location;

            });
           

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
            console.log(result);
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
    var  geoRoute = [];
    var echantRoute = [];
    $.each(route, function(index, value)
    {
        geoRoute.push(new google.maps.LatLng(value.latitude, value.longitude));
    });
    length = google.maps.geometry.spherical.computeLength(geoRoute);
    sinuosity = route.length / length;
    var counter= 10;
    if(geoRoute.length > 4000){
        counter =20;
    }
   if(geoRoute.length > 8000){
        counter =30;
    }
    if(geoRoute.length > 12000){
        counter =50;
    }
     for (var i = 0; i < geoRoute.length-counter; i+=counter) {
         echantRoute.push(geoRoute[i]);
     }     
    elevator.getElevationAlongPath({
        'path': echantRoute,
        'samples' : 256
    }, function(elevations, status) {
        if (status !== 'OK') {
            console.log(status);
        } else {
            var elevationAverage=0;            
            for (var i = 1; i < elevations.length; i++) {
               elevationAverage += Math.abs(elevations[i-1].elevation- elevations[i].elevation);
            }
            console.log(elevationAverage);
            $.ajax({
                url: './Includes/ajax.php',
                type: 'POST',
                data: {fonction: "SaveNewRoute", idRoute: idroute, route: route, sinuosite: sinuosity,elevation:elevationAverage},
                async: false,
                success: function(result) {

                }
            });
        }
    });

}

$(document).ready(function() {
    initMap();
    clear();
    RouteClick();   
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
            editlines();
        }
    });
    $("#RefreshRoute").click(function() {
        console.log("Refresh");
        //
        if (oldMarkers.length === Markers.length) {
            console.log("old route");
            console.log(route);
            var j = 0;
            for (var i = 0; i < Markers.length; i++) {
                var from = [];
                from["lat"] = Markers[i].position.lat();
                from["lon"] = Markers[i].position.lng();
                var to = [];
                to["lat"] = oldMarkers[i].position.lat();
                to["lon"] = oldMarkers[i].position.lng();
                searchIti(from, to);
                if (i < Markers.length - 1)
                    while ((oldRoute[j].lat() !== oldMarkers[i + 1].position.lat()) && (oldRoute[j].lng() !== oldMarkers[i + 1].position.lng())) {
                        route.push(oldRoute[j]);
                        console.log("push");
                        j++;
                    }
                else
                    while (j < oldRoute.length) {
                        route.push(oldRoute[j]);
                        j++;
                    }
            }
            console.log("route");
            console.log(route);

            /*  oldRoute.shift()
             oldRoute.unshift(route[0][0], route[0][1]);
             //  oldRoute[oldRoute.length-1] = route[1];
             route = oldRoute;
             DisplayRoute();
             console.log("new route");
             console.log(route);*/
        }
    });
    $("#sinuosity").change(function() {
        RefreshhRoutesWithFilters();
    });
    $("#slope").change(function() {
        RefreshhRoutesWithFilters();
    });
    $("#highway").change(function() {
        RefreshhRoutesWithFilters();
    });
    $("#StartCreation").click(function(){
        StartCreation();
    })
});

$(document).bind({
    ajaxStart: function() { $("body").addClass("loading"); },
     ajaxStop: function() { $("body").removeClass("loading"); }    
});

function RouteClick() {
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
}

function searchIti(from, to) {

    var request = {
        origin: new google.maps.LatLng(from["lat"], from["lon"]),
        destination: new google.maps.LatLng(to["lat"], to["lon"]),
        travelMode: google.maps.TravelMode.DRIVING
                //WALKING / DRIVING / BICYCLING / TRANSIT / 
    };
    route = [];
    directionsDisplay.setMap(this.map);
    directionsService.route(request, function(result, status) {
        var distanceM = result.routes[0].legs[0].distance.value;
        var tempsS = result.routes[0].legs[0].duration.valsue;

        if (status === google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(result);
            $.each(result.routes[0].overview_path, function(index, point) {
                route.push(point);
            });

        }

    });

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


    if (parcoursModif === true) {
        //crée les marqueurs de début et fin de route
        CreateMarker(liste_des_points[0], parcoursModif, false, "36af2d", "%E2%80%A2", 0);
        CreateMarker(liste_des_points[liste_des_points.length - 1], parcoursModif, false, "a52424", "%E2%80%A2", 1);
    }

    $.each(liste_des_points, function(index, value)
    {
        route.push(new google.maps.LatLng(value.Latitude, value.Longitude));
    });

    var bounds = new google.maps.LatLngBounds();

    for (var i = 0; i < liste_des_points.length; i++) {
        bounds.extend(new google.maps.LatLng(liste_des_points[i].Latitude, liste_des_points[i].Longitude));
    }
    map.fitBounds(bounds);
    DisplayRoute(color);
}

function DisplayRoute(color) {
    color = color || "#ff1ece";
    parcoursPolyline = new google.maps.Polyline({
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

function DownloadRoute() {
    if (highlighted == null) {
        alert("Please select a trip");
    } else
    {
        $.ajax({
            url: './Includes/ajax.php',
            type: 'POST',
            data: {fonction: "Download", name: $(highlighted).html(), path: JSON.stringify(route)},
            dataType: "html",
            async: false,
            success: function(result) {
                console.log("ICI");
                document.location.href = "./Includes/download.php?file=" + result;
            },
            error: function(result, status, error) {
                console.log(result);
                console.log(status);
                console.log(error);
            }
        });
    }
}

function DisplayInfo() {

}

function EnabledDisabledFilters(caller) {
    if (caller.checked) {
        $("#filters").removeAttr("hidden");
        RefreshhRoutesWithFilters();
    } else {
        $("#filters").attr("hidden", true);
    }
}

function RefreshhRoutesWithFilters() {
    var sinuosity = $("#sinuosity").val();
    var slope = $("#slope").val();
    var highway = $("#highway").prop("checked");
    console.log(sinuosity);
    console.log(slope);
    console.log(highway);
    $.ajax({
        url: './Includes/ajax.php',
        type: 'GET',
        data: {fonction: "FilterRoad", sinuosity: sinuosity, slope: slope, highway: highway, time: 1},
        dataType: "json",
        success: function(result) {
            console.log(result);
            $(".routes").empty();
            $.each(result, function(index, value)
            {
                $(".routes").append("<div class=\"route\" name=\"" + value.idRoute + "\">" + value.RouteName + "<span class=\"by\"> by "+ value.Username +"</span></div>");
            });
            RouteClick();
        }

    });

}

function StartCreation(){
    initMap(true);
}

/************************************************************************/
/*              http://www.birdtheme.org/useful/v3tool.html             */
/************************************************************************/

function editlines() {
    Markers = [];
    route = parcoursPolyline.getPath();
    if (route.length > 0) {
        for (var i = 0; i < route.length; i++) {
            var marker = setmarkers(route.getAt(i));
            Markers.push(marker);
            if (i > 0) {
                var midmarker = setmidmarkers(route.getAt(i));
                midmarkers.push(midmarker);
            }
        }
    }
}
function setmarkers(point) {
    var marker = new google.maps.Marker({
        position: point,
        map: map,
        icon: imageNormal,
        raiseOnDrag: false,
        draggable: true
    });
    google.maps.event.addListener(marker, "mouseover", function() {
        marker.setIcon(imageHover);
    });
    google.maps.event.addListener(marker, "mouseout", function() {
        marker.setIcon(imageNormal);
    });
    google.maps.event.addListener(marker, "drag", function() {
        for (var i = 0; i < Markers.length; i++) {
            if (Markers[i] == marker) {
                parcoursPolyline.getPath().setAt(i, marker.getPosition());
                movemidmarker(i);
                break;
            }
        }
        route = parcoursPolyline.getPath();
        var stringtobesaved = marker.getPosition().lat().toFixed(6) + ',' + marker.getPosition().lng().toFixed(6);
        pointsArray.splice(i, 1, stringtobesaved);
    });
    google.maps.event.addListener(marker, "click", function() {
        for (var i = 0; i < Markers.length; i++) {
            if (Markers[i] == marker && Markers.length != 1) {
                marker.setMap(null);
                Markers.splice(i, 1);
                parcoursPolyline.getPath().removeAt(i);
                //   removemidmarker(i);
                break;
            }
        }
        polyPoints = parcoursPolyline.getPath();
        if (Markers.length > 0) {
            pointsArray.splice(i, 1);
            logCode1();
        }
    });
    return marker;
}
function setmidmarkers(point) {
    var prevpoint = Markers[Markers.length - 2].getPosition();
    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(
                point.lat() - (0.5 * (point.lat() - prevpoint.lat())),
                point.lng() - (0.5 * (point.lng() - prevpoint.lng()))
                ),
        map: map,
        icon: imageNormalMidpoint,
        raiseOnDrag: false,
        draggable: true
    });
    google.maps.event.addListener(marker, "mouseover", function() {
        marker.setIcon(imageNormal);
    });
    google.maps.event.addListener(marker, "mouseout", function() {
        marker.setIcon(imageNormalMidpoint);
    });

    google.maps.event.addListener(marker, "dragend", function() {
        for (var i = 0; i < midmarkers.length; i++) {
            if (midmarkers[i] == marker) {
                var newpos = marker.getPosition();
                var startMarkerPos = Markers[i].getPosition();
                var firstVPos = new google.maps.LatLng(
                        newpos.lat() - (0.5 * (newpos.lat() - startMarkerPos.lat())),
                        newpos.lng() - (0.5 * (newpos.lng() - startMarkerPos.lng()))
                        );
                var endMarkerPos = Markers[i + 1].getPosition();
                var secondVPos = new google.maps.LatLng(
                        newpos.lat() - (0.5 * (newpos.lat() - endMarkerPos.lat())),
                        newpos.lng() - (0.5 * (newpos.lng() - endMarkerPos.lng()))
                        );
                var newVMarker = setmidmarkers(secondVPos);
                newVMarker.setPosition(secondVPos);//apply the correct position to the midmarker
                var newMarker = setmarkers(newpos);
                Markers.splice(i + 1, 0, newMarker);
                parcoursPolyline.getPath().insertAt(i + 1, newpos);
                marker.setPosition(firstVPos);
                midmarkers.splice(i + 1, 0, newVMarker);
                /*tmpPolyLine.getPath().removeAt(2);
                 tmpPolyLine.getPath().removeAt(1);
                 tmpPolyLine.getPath().removeAt(0);*/
                break;
            }
        }
        polyPoints = parcoursPolyline.getPath();
        var stringtobesaved = newpos.lat().toFixed(6) + ',' + newpos.lng().toFixed(6);
        pointsArray.splice(i + 1, 0, stringtobesaved);
        logCode1();
    });
    return marker;
}
function movemidmarker(index) {
    var newpos = Markers[index].getPosition();
    if (index != 0) {
        var prevpos = Markers[index - 1].getPosition();
        midmarkers[index - 1].setPosition(new google.maps.LatLng(
                newpos.lat() - (0.5 * (newpos.lat() - prevpos.lat())),
                newpos.lng() - (0.5 * (newpos.lng() - prevpos.lng()))
                ));
    }
    if (index != Markers.length - 1) {
        var nextpos = Markers[index + 1].getPosition();
        midmarkers[index].setPosition(new google.maps.LatLng(
                newpos.lat() - (0.5 * (newpos.lat() - nextpos.lat())),
                newpos.lng() - (0.5 * (newpos.lng() - nextpos.lng()))
                ));
    }
}
function removemidmarker(index) {
    if (Markers.length > 0) {//clicked marker has already been deleted
        if (index != Markers.length) {
            midmarkers[index].setMap(null);
            midmarkers.splice(index, 1);
        } else {
            midmarkers[index - 1].setMap(null);
            midmarkers.splice(index - 1, 1);
        }
    }
    if (index != 0 && index != Markers.length) {
        var prevpos = Markers[index - 1].getPosition();
        var newpos = Markers[index].getPosition();
        midmarkers[index - 1].setPosition(new google.maps.LatLng(
                newpos.lat() - (0.5 * (newpos.lat() - prevpos.lat())),
                newpos.lng() - (0.5 * (newpos.lng() - prevpos.lng()))
                ));
    }
}

