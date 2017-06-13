/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var map;
var tabMarqueurs = [];
var parcours = [];

function initMap() {
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
}


$(document).ready(function() {
    initMap();
    $(".route").click(function() {
        $.ajax({
            url: './Includes/ajax.php',
            type: 'GET',
            data: {fonction: "GetRoutePoints", idRoute: $(".route").attr('name')},
            dataType: "json",
            success: function(result) {
             
               
                        console.log("Go to show");
                        ShowParcours(result, false);
   
            }
        });
    });
});

function clear() {
    tabMarqueurs = [];
    parcours = [];
}

/* Affiche les points
 @param array tableauPoints : Tableau des marqueurs Ã  afficher sur la map
 @param bool parcoursModif : savoir si les marqueurs parcours
 est modifiable ou pas(dÃ©plaÃ§able, supprimable)
 */
function ShowParcours(tableauPoints, parcoursModif) {
// Initialise un tableau avec le tableau json decriptÃ©
    var liste_des_points = tableauPoints;
    gPolylines = new Array();
    // Pour chaque point dans le tableau
    $.each(liste_des_points, function(index, value)
    {
        // CrÃ©ait un nouveau marker
        parcoursMarker = new google.maps.Marker({
            position: new google.maps.LatLng(value.Latitude, value.Longitude),
            draggable: parcoursModif, //  Rendre le marqueur dÃ©plaÃ§able ou pas
            zIndex: length++, //  Ajout d'un index pour le retrouver plus facilement (suppression)
            map: map    //  Map sur laquelle le marqueur va se trouver
        });
        // Centrer la map sur le premier parcours
        if (index === 0) {
            map.panTo(parcoursMarker.getPosition());
        }

        //  Si le marqueur est charger sur la page de modification
        if (parcoursModif) {
            //  On ajoute un Ã©vÃ©nement pour supprimer le marqueur
            parcoursMarker.addListener('click', function() {
                //  Suppression du marqueur sur la map
                this.setMap(null);
                //  Suppression du marqueur dans le tableau
                tabMarqueurs.splice(tabMarqueurs.indexOf(this), 1);
                length--;
            });
            //  EvÃ©nement de fin de dÃ©placement de marqueur
            parcoursMarker.addListener('dragend', function() {
                tabMarqueurs[this.zIndex].latLng = this.position;
            });
        }

        parcours.push(new google.maps.LatLng(value.Latitude, value.Longitude));
        //  Ajout du marqueur dans le tableau
        tabMarqueurs.push(parcoursMarker);
    });
    var bounds = new google.maps.LatLngBounds();
    //  Si le tableau est vide
    if (tabMarqueurs.length == 0) {
//  On centre la vue de la map autour de GenÃ¨ve
        bounds.extend({lat: 46.2, lng: 6.1667});
        bounds.extend({lat: 46.2, lng: 6.12});
    }

    for (var i = 0; i < tabMarqueurs.length; i++) {
        bounds.extend(tabMarqueurs[i].getPosition());
    }
    map.fitBounds(bounds);
    parcoursPolyline = new google.maps.Polyline({
        path: parcours,
        strokeColor: "#FF0000",
        strokeOpacity: 1.0,
        strokeWeight: 4,
        map: map
    });
    gPolylines.push(parcoursPolyline);
}