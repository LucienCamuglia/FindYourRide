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
                Points = SnappPoints2Road(Points);
                SaveNewLocation(<?php echo $idRoute ?>, Points);
    <?php
}
?>
        </script>
        <div class=" routes col-lg-offset-1 col-lg-2">

            <?php
            $routes = GetRoutes();
            foreach ($routes as $route) {
                echo"<div class=\"route\" name=\"" . $route["idRoute"] . "\">" . $route["RouteName"] . "</div>";
            }
            ?>            


        </div>
        <div id="map" class="col-lg-8" style="height: 80%;"></div>
        
        <div class="btnZone col-lg-offset-5 top-buffer"  >
            <button id="btnModif"  class="hidden btn btn-primary" name="none">Modifier le trajet</button>

            <button class="btn">Créer un nouveau trajet</button>
            <button class="btn"data-toggle="modal" data-target="#modalimport" onclick='$("#msgCont").attr("Hidden", "true")' >Importer un nouveau trajet</button>
                    
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
        
    </body>
</html>
