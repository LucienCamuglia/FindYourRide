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
                <p><label>Sinuosity</label><input id="sinuosity" type="range"  min="0" max="<?php echo GetMostSinuousRoad()+1; ?>" /></p>
                <p><label>Slope</label><input id="slope" type="range"  min="<?php echo GetLessSteepestRoad(); ?>" max="<?php echo GetMostSteepestRoad();?>" /></p>
                <p><label>Highway </label><input id="highway" class="col-sm-3 checkbox-inline"  type="checkbox"  /></p>

            </div>
        </div>
        <div class="routes col-lg-2 " style="">          
            <?php
            $routes = GetRoutes();
            foreach ($routes as $route) {
                echo"<div class=\"route\" name=\"" . $route["idRoute"] . "\">" . $route["RouteName"] . "<span class=\"by\"> by ". $route["Username"] ."</span></div>";
            }
            ?>            

        </div>
        <div id="map" class="col-lg-8" style="height: 80%;"></div>
        <div><button class="btn btn-primary" onclick="DownloadRoute();" >Download route</button></div>
        <div class="col-lg-8 col-lg-offset-2" >

        </div>
    </body>
</html>
