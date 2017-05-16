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

        <div class="routes col-lg-2 col-lg-offset-1 " style="">          
                <?php
                $routes = GetRoutes();
                foreach ($routes as $route) {
                    echo"<div class=\"route\" name=\"".$route["idRoute"]."\">".$route["RouteName"]."</div>";
                }
                ?>            

        </div>
        <div id="map" class="col-lg-8" style="height: 80%;"></div>
        <div><button class="btn btn-primary" onclick="DownloadRoute();" >Download route</button></div>
    </body>
</html>
