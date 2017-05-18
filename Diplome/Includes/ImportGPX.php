<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once './functions.php';
session_start();
if (isset($_POST["import"])) {
    if (isset($_FILES["GpxFile"])) {
        if (pathinfo($_FILES["GpxFile"]["name"])["extension"] == "gpx") {
            $containsHighway = isset($_POST["highway"]);
            // print_r($_FILES["GpxFile"]);          
            $idRoute = Gpx2Sql($_FILES["GpxFile"]["tmp_name"], $_SESSION["id"], $containsHighway);
             header("location: ../itineraire.php?NewIti&id=$idRoute");
        } else {
            echo"extension GPX nécéssaire";
        }
    }
}



