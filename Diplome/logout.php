<?php
/*
 * Author : Lucien Camuglia
 * Description : log out an user
 * Date : April-june 2017
 * Version : 1.0 LC BaseVersion
 */

session_start();
session_destroy();
header("location: ./index.php");

