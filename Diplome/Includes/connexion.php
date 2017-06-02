<?php
/*
 * Author : Lucien Camuglia
 * Description : Connexion file
 * Date : April-june 2017
 * Version : 1.0 LC BaseVersion
 */

session_start();
include_once './functions.php';

if (!empty($_POST['username'])) {
    if (!empty($_POST['password'])) {
        $connexion = ConnexionUser($_POST['username'], sha1($_POST['password']));
        if (!$connexion) {
            $reponse_array['status'] = 'error';
            $reponse_array['message'] = 'wrong username or password';
        } else {
            if ($_SESSION["role"] == 3) {
                session_destroy();
                $reponse_array['status'] = 'error';
                $reponse_array['message'] = 'You are banned';
            } else {
                $reponse_array['status'] = 'success';
            }
        }
    } else {
        $reponse_array['status'] = 'error';
        $reponse_array['message'] = 'password is required';
    }
} else {
    $reponse_array['status'] = 'error';
    $reponse_array['message'] = 'username is required';
}

header('Content-type: application/json');
echo json_encode($reponse_array);
