<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <?php include './Includes/header.php'; ?>        
        <title></title>
    </head>
    <body style="height: 500px;" >
        <?php
        DisplayMenu(basename($_SERVER['PHP_SELF']));
        importerTrajetModal();
        newRouteModal();
        if (isset($_GET["NewIti"])) {
            $idRoute = $_GET["id"];
        }
        ?>
        <script src="./JS/Map.js"></script>
        <script>
<?php
if (isset($idRoute)) {
    ?>
                var Points = LoadPoints(<?php echo $idRoute ?>);
                console.log(Points);
                Points = SnappPoints2Road(Points);
                SaveNewLocation(<?php echo $idRoute ?>, Points);
    <?php
}
?>
        </script>
        <div class=" routes col-lg-offset-1 col-lg-2">

            <?php
            $routes = GetRoutes($_SESSION["id"]);
            foreach ($routes as $route) {
                echo"<div class=\"route\" name=\"" . $route["idRoute"] . "\">" . $route["RouteName"] . "</div>";
            }
            ?>            


        </div>
        <input id="pac-input" class="controls" type="text" placeholder="Search Box" hidden/>
        <div id="map" class="col-lg-7" style="height: 80%;"></div>

        <div class="btnZone col-lg-2 top-buffer"  >
           <!-- <button id="btnModif"  class="hidden btn btn-primary btn-block" name="none">Modifier le trajet</button>-->

            <label class="checkbox-inline SaveRouteControls hidden">
                <input id="chkHighway" type="checkbox" checked data-toggle="toggle"> Highways
            </label>
           
            <button id="SaveRoute" class=" btn btn-primary btn-block SaveRouteControls hidden" data-target="#newRoutemodal" data-toggle="modal">Save the route</button>
            <button id="ClearRoute" class=" btn btn-primary btn-block SaveRouteControls hidden">Clear the route</button>

            <button class="btn btn-block" id="StartCreation">Créer un nouveau trajet</button>
            <button class="btn  btn-block" data-toggle="modal" data-target="#modalimport" onclick='$("#msgCont").attr("Hidden", "true")' >Importer un nouveau trajet</button>

            <button id="RefreshRoute" class=" btn btn-primary  hidden modifRoute">Refresh the route</button>

        </div>

        <div id="pointsInfo" class="col-lg-6 col-lg-offset-3 hidden modifRoute"  >

            <div id="pointStart">
                <div class="Pointname col-lg-12" >
                    <span class="col-lg-2 align-middle" style="height: 100px;" >Start : </span>
                    <div class="col-lg-10" >
                        <div>
                            <label class="col-sm-3">Latitude : </label> <input id="inpLatPoint0" type="text" value="0.000">
                        </div>
                        <div>
                            <label class="col-sm-3">Longitude : </label> <input id="inpLonPoint0" type="text" value="0.000">
                        </div>
                    </div>   
                </div>

            </div>    

            <div id="pointEnd">
                <div class="Pointname col-lg-12" >
                    <span class="col-lg-2 align-middle" style="height: 100%;" >End : </span>
                    <div class="col-lg-10" >
                        <div>
                            <label class="col-sm-3">Latitude : </label> <input  id="inpLatPoint1"  type="text" value="0.000">
                        </div>
                        <div>
                            <label class="col-sm-3">Longitude : </label> <input  id="inpLonPoint1"  type="text" value="0.000">
                        </div>
                    </div>     
                </div>            
            </div>                               

        </div>
        <div class="modalLoad"></div>
    </body>
</html>
