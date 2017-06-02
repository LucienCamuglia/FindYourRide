<?php
/*
 * Author : Lucien Camuglia
 * Description : Download a file
 * Date : April-june 2017
 * Version : 1.0 LC BaseVersion
 */

if (isset($_GET['file'])) {
    $file = $_GET['file'];
    //verify if file exists
    if (file_exists($file) && is_readable($file)) {
        //prepare to download
        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename=\"$file\"");
        //download the file
        readfile($file);
        //delete it
        unlink($file);
    } else {
        //display an 404 error
        header("HTTP/1.0 404 Not Found");
        echo "<h1>Error 404: File Not Found: <br /><em>$file</em></h1>";
    }
}

