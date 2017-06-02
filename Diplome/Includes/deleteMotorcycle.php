<?php
/*
 * Author : Lucien Camuglia
 * Description : Delete a motorcycle 
 * Date : April-june 2017
 * Version : 1.0 LC BaseVersion
 */

session_start();
include_once './functions.php';

if(isset($_SESSION["role"]) && $_SESSION["role"]==1){
    if(isset($_POST["delete"])){
        if(isset($_POST["toDelete"])){
            deleteMotorcycle($_POST["toDelete"]);
            header('location: ../manageMoto.php');
            exit();
        }
    }
}

 header('location: ../index.php');

exit();