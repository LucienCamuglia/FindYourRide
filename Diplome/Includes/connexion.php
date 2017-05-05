<?php

//démarre les sessions
session_start();

include_once './functions.php';

if (!empty($_POST['username'])) {
    if (!empty($_POST['password'])) {
        $connexion = ConnexionUser($_POST['username'], sha1($_POST['password']));
        if (!$connexion) {
            $reponse_array['status'] = 'error';
            $reponse_array['message'] = 'wrong username or password';
        } else {
            $reponse_array['status'] = 'success';            
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