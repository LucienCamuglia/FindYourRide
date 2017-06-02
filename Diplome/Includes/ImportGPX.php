<?php
/*
 * Author : Lucien Camuglia
 * Description : Import a GPX file 
 * Date : April-june 2017
 * Version : 1.0 LC BaseVersion
 */
include_once './functions.php';
session_start();
//verify if import has been pressed
if (isset($_POST["import"])) {
    //veriy if a file is selected
    if (isset($_FILES["GpxFile"])) {
        //verify if the file format is gpx
        if (pathinfo($_FILES["GpxFile"]["name"])["extension"] == "gpx") {
            //set if the road contains highway
            $containsHighway = isset($_POST["highway"]);
            // save the file on the server        
            $idRoute = Gpx2Sql($_FILES["GpxFile"]["tmp_name"], $_SESSION["id"], $containsHighway);
            //redirect the user
             header("location: ../itineraire.php?NewIti&id=$idRoute");
        } else {
            header("location: ../itineraire.php?modal=modalimport&error=extension%20GPX%20nécéssaire");
        }
    }
}



