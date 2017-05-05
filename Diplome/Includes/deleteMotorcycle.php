<?php

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