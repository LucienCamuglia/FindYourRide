<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <?php include './Includes/header.php' ?>
        <title></title>
    </head>
    <body style="height: 500px;" >
        <?php
        DisplayMenu(basename($_SERVER['PHP_SELF']));
        ?>
        <script src="./JS/Map.js"></script>
        <script>

        </script>
        <div class="col-sm-1 "><h2>Filters  :</h2> 
            <h6 class="col-xs-9">Enabled</h6> <input class="col-sm-3 checkbox-inline" type="checkbox" onclick="EnabledDisabledFilters(this)"/>
            <div id="filters" hidden="true">
                <p><label>Sinuosity</label><input id="sinuosity" type="range"  min="<?php echo GetLessSinuousRoad(); ?>" max="<?php echo GetMostSinuousRoad() + 1; ?>" /></p>
                <p><label>Slope</label><input id="slope" type="range"  min="<?php echo GetLessSteepestRoad(); ?>" max="<?php echo GetMostSteepestRoad(); ?>" /></p>
                <p><label>Highway </label><input id="highway" class="col-sm-3 checkbox-inline"  type="checkbox"  /></p>

            </div>
        </div>
        <div class="routes col-lg-2 " style="height: 110%">          
            <?php
            $routes = GetRoutes();
            foreach ($routes as $route) {
                echo"<div class=\"route\" name=\"" . $route["idRoute"] . "\">" . $route["RouteName"] . "<span class=\"by\"> by " . $route["Username"] . "</span></div>";
            }
            ?>            

        </div>
        <input id="pac-input" class="controls" type="text" placeholder="Search Box" hidden>
        <div id="map" class="col-lg-8" style="height: 110%;"></div>
        <div><button class="btn btn-primary" onclick="DownloadRoute();" >Download route</button></div>
        <div class="col-lg-5 col-lg-offset-4" >
            <center><h2>Road Infos</h2></center>
            <h4 class="col-lg-12" id="InfoHighway">This road does not includes highways</h4>
            <p><span style="text-align: left;" class="col-lg-6" id="InfoLength">Length : 12km</span><span style="text-align: right;" class="col-lg-6" id="InfoDuration">Duration : 0h30min</span></p>
            <p><span style="text-align: left;" class="col-lg-6" id="InfoSinuosity">Sinuosity : 0/10</span><span style="text-align: right;" class="col-lg-6" id="InfoSlope">Slope : 0/10</span></p>
            <p><span style="text-align: left;" class="col-lg-8" id="InfoConsumption">Consumption : 52 liters</span><span style="text-align: right;" class="col-lg-6"></span></p>

        </div>
    </body>
</html>
