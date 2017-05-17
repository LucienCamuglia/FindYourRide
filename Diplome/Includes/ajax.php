<?php

session_start();
include './functions.php';
if (isset($_REQUEST["fonction"])) {
    $function = $_REQUEST["fonction"];

    switch ($function) {
        case "GetModel" : GetModel($_GET["Brand"]);
            break;
        case "GetYear" : GetYear($_GET["Brand"], $_GET["Model"]);
            break;
        case "UserExists" : UserExists($_GET["Username"]);
            break;
        case "GetMotorcycles" : GetMotorcycles($_GET["Brand"], $_GET["Model"], $_GET["Year"], $_GET["Consumption"], $_GET["Tiredness"]);
            break;
        case"GetRoutePoints":GetRoutePoints($_GET["idRoute"]);
            break;
        case "SaveNewRoute" : SaveNewRoute($_POST["idRoute"], $_POST["route"]);
            break;
        case "AddMotorcycle" : AddMotorcycle($_GET["Brand"], $_GET["Model"], $_GET["Year"], $_GET["Consumption"], $_GET["Tiredness"]);
            break;

        case "UpdateUserRole" : UpdateUserRole($_GET["idUser"], $_GET["Role"]);
            break;
        case "GetUserRoleById" : GetUserRole($_GET["idUser"]);
            break;
        case "Download" : downloadRoute($_REQUEST["name"], $_REQUEST["path"]);
            break;
        default : exit();
            break;
    }
}exit();

function GetModel($brand) {
    $query = "select distinct model from moto where Brand = :brand ";
    $params = array('brand' => $brand);
    $st = PrepareExecute($query, $params);
    while ($data = $st->fetch(PDO::FETCH_ASSOC)) {
        $array_response[] = $data["model"];
    }
    echo json_encode($array_response);
}

function GetYear($brand, $model) {
    $query = "select distinct year from moto where Brand = :brand  and model = :model order by year";
    $params = array('brand' => $brand, 'model' => $model);
    $st = PrepareExecute($query, $params);
    while ($data = $st->fetch(PDO::FETCH_ASSOC)) {
        $array_response[] = substr($data["year"], 0, 4);
    }
    echo json_encode($array_response);
}

function UserExists($username) {
    $query = "select idUser from users where Username = :username";
    $params = array('username' => $username);
    $st = PrepareExecute($query, $params);
    $array_response["exist"] = false;
    while ($data = $st->fetch(PDO::FETCH_ASSOC))
        $array_response["exist"] = true;

    echo json_encode($array_response);
}

function GetMotorcycles($brand, $model, $year, $consumption, $tiredness) {
    $query = "Select * from moto where Brand like :brand and model like :model and year like :year and consumption like :consumption and Tiredness like :tiredness order by Brand";
    $params = array('brand' => $brand, 'model' => $model, 'year' => $year, 'consumption' => $consumption, 'tiredness' => $tiredness);
    $st = PrepareExecute($query, $params);
    $array_response = "";
    while ($data = $st->fetch(PDO::FETCH_ASSOC)) {
        $array_response[] = $data;
    }
    echo json_encode($array_response);
}

function GetRoutePoints($idRoute) {
    $query = "Select * from place where idRoute=:idRoute order by position;";
    $params = array('idRoute' => $idRoute);
    $st = PrepareExecute($query, $params);
    $array_response = "";
    while ($data = $st->fetch(PDO::FETCH_ASSOC)) {
        $array["Latitude"] = $data["latitude"];
        $array["Longitude"] = $data["longitude"];
        $array_response[] = $array;
    }

    echo json_encode($array_response);
}

function SaveNewRoute($idroute, $route) {
    deleatePlaces($idroute);
    $position = 0;
    foreach ($route as $point) {
        $lat = $point["latitude"];
        $lon = $point["longitude"];
        addPlaceToRoute($lat, $lon, $position++, $idroute);
    }
}

function AddMotorcycle($brand, $model, $year, $consumption, $tiredness) {
    $array_response = [];
    $array_response["error"]["status"] = false;
    $array_response["error"]["message"] = "";

    if (!empty($brand) && !empty($model) && !empty($year) && !empty($consumption) && !empty($tiredness)) {
        $query = "Insert Into moto (Brand, model, year, consumption, Tiredness) values (:brand, :model, :year, :consumption, :tiredness);";
        $params = array('brand' => $brand, 'model' => $model, 'year' => $year . '-01-01', 'consumption' => $consumption, 'tiredness' => $tiredness);
        PrepareExecute($query, $params);
    } else {
        $array_response["error"]["status"] = true;
        $array_response["error"]["message"] = "Please fill all field";
    }

    echo json_encode($array_response);
}

function UpdateUserRole($idUser, $idRole) {
    $array_response = [];
    $array_response["error"]["status"] = false;
    $array_response["error"]["message"] = "";

    if (isset($_SESSION["role"]) && $_SESSION["role"] == 1) {

        $query = "UPDATE users SET role = :role WHERE idUser=:idUser;";
        $params = array('role' => $idRole, 'idUser' => $idUser);
        $st = PrepareExecute($query, $params);
        $Newrole;
        switch (getUserRoleById($idUser)) {
            case 1: $Newrole = "Administrator";
                break;
            case 2: $Newrole = "User";
                break;
            case 3: $Newrole = "Ban";
                break;
            default : $Newrole = "error";
                break;
        }
        $array_response["datas"]["NewRole"] = $Newrole;
    } else {
        $array_response["error"]["status"] = true;
        $array_response["error"]["message"] = "Rights needed";
    }

    echo json_encode($array_response);
}

function GetUserRole($idUser) {
    $result;
    switch (getUserbRoleById($idUser)) {
        case 1: $result = "Administrator";
            break;
        case 2: $result = "User";
            break;
        case 3: $result = "Ban";
            break;
    }
    echo json_encode($result);
}

function downloadRoute($name, $path) {
    $file = Path2Gpx($name, $path);
    echo $file;
}
