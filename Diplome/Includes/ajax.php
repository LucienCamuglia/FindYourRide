<?php
/*
 * Author : Lucien Camuglia
 * Description : contains all function used in ajax 
 * Date : April-june 2017
 * Version : 1.0 LC BaseVersion
 */

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
        case "SaveNewRoute" : SaveNewRoute($_REQUEST["idRoute"], $_REQUEST["route"], $_REQUEST["sinuosite"], $_REQUEST["elevation"], $_REQUEST["length"]);
            break;
        case "AddMotorcycle" : AddMotorcycle($_GET["Brand"], $_GET["Model"], $_GET["Year"], $_GET["Consumption"], $_GET["Tiredness"]);
            break;

        case "UpdateUserRole" : UpdateUserRole($_GET["idUser"], $_GET["Role"]);
            break;
        case "GetUserRoleById" : GetUserRole($_GET["idUser"]);
            break;
        case "Download" : downloadRoute($_REQUEST["name"], $_REQUEST["path"]);
            break;
        case "FilterRoad" : FilterRoad($_GET["sinuosity"], $_GET["slope"], $_GET["highway"], $_GET["time"]);
            break;
        case "GetRoutes" : GetRoutesJSON();
            break;
        case "GetRoadsInfos" : GetRoadsInfos($_GET["idRoute"]);
            break;
        case "CreateRoute" : CreateNewRoute($_GET["name"], $_GET["containsHighway"]);
            break;
        case "DeleteRoute" : DeleteRoute($_GET["idRoute"]);
            break;
        default : exit();
            break;
    }
}else{
    header('location: ../index.php');   
}
exit();

//Retrun motorcycle models by brand
function GetModel($brand) {
    $query = "select distinct model from moto where Brand = :brand ";
    $params = array('brand' => $brand);
    $st = PrepareExecute($query, $params);
    while ($data = $st->fetch(PDO::FETCH_ASSOC)) {
        $array_response[] = $data["model"];
    }
    echo json_encode($array_response);
}

//Retrun motorcycle's year by brand and model
function GetYear($brand, $model) {
    $query = "select distinct year from moto where Brand = :brand  and model = :model order by year";
    $params = array('brand' => $brand, 'model' => $model);
    $st = PrepareExecute($query, $params);
    while ($data = $st->fetch(PDO::FETCH_ASSOC)) {
        $array_response[] = substr($data["year"], 0, 4);
    }
    echo json_encode($array_response);
}

//check if an user exists
function UserExists($username) {
    $query = "select idUser from users where Username = :username";
    $params = array('username' => $username);
    $st = PrepareExecute($query, $params);
    $array_response["exist"] = false;
    while ($data = $st->fetch(PDO::FETCH_ASSOC))
        $array_response["exist"] = true;

    echo json_encode($array_response);
}

//Return all motorcycles according to input criteria
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

//Get all points of a road
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

//save a new route
function SaveNewRoute($idroute, $route, $sinueusite, $elevation, $length) {
    //delete all places from this road
    deleatePlaces($idroute);
    
    AddSinuosity($idroute, $sinueusite);
    AddElevation($idroute, $elevation);
    AddLength($idroute, $length);
    $position = 0;
    $fromweb = false;
    if (is_string($route)) {
        $route = json_decode($route);
        $fromweb = true;
    }
    foreach ($route as $point) {
        if ($fromweb) {
            $lat = $point->lat;
            $lon = $point->lng;
        } else {
            $lat = $point["latitude"];
            $lon = $point["longitude"];
        }
        addPlaceToRoute($lat, $lon, $position++, $idroute);
    }
}

//add a new motorcycle
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

//update a user role
function UpdateUserRole($idUser, $idRole) {
    $array_response = [];
    $array_response["error"]["status"] = false;
    $array_response["error"]["message"] = "";

    //check if the user is admin
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

//get user role by id
function GetUserRole($idUser) {
    $result;
    switch (getUserRoleById($idUser)) {
        case 1: $result = "Administrator";
            break;
        case 2: $result = "User";
            break;
        case 3: $result = "Ban";
            break;
    }
    echo json_encode($result);
}

//download prepare to download and return filename
function downloadRoute($name, $path) {
    $file = Path2Gpx($name, $path);
    echo $file;
}

//return a filtered road 
function FilterRoad($sinuosity, $slope, $highway, $time) {
    $query = "Select * from route natural join users where Sinuosity <= :sinuosity AND Slope <= :slope AND Time <= :time AND highway = :highway ORDER BY RouteName;";
    // $query  = "Select * from route where Sinuosity <= 135 AND Slope <= 0 AND Time <= 1 AND highway = 0;";
    $params = array(
        "sinuosity" => $sinuosity / 10000,
        "slope" => $slope,
        "time" => $time,
        "highway" => ($highway == "true") ? '1' : '0');
    $st = PrepareExecute($query, $params);
    $array_response = [];
    while ($data = $st->fetch(PDO::FETCH_ASSOC)) {
        $array["RouteName"] = $data["RouteName"];
        $array["idRoute"] = $data["idRoute"];
        $array["Username"] = $data["Username"];
        $array_response[] = $array;
    }

    echo json_encode($array_response);
}

//return all routes in JSON
function GetRoutesJSON() {
    $routes = GetRoutes();
    echo json_encode($routes);
}

//get roads info
function GetRoadsInfos($idRoute) {
    $query = "Select * from route where idRoute = :idRoute";
    $params = Array("idRoute" => $idRoute);
    $st = PrepareExecute($query, $params);
    $datas = $st->fetch(PDO::FETCH_ASSOC);
    $array_response["Length"] = $datas["Length"] / 1000;
    $array_response["Highway"] = $datas["highway"];
    $array_response["Time"] = $datas["Time"];
    $array_response["Sinuosity"] = (int) (($datas["Sinuosity"] * 10000 / GetMostSinuousRoad()) * 10);
    $array_response["Slope"] = round($datas["Slope"] / GetMostSteepestRoad() * 10);

    //if th euser is connected, return motorcycle consumption if not, return 0
    if (isset($_SESSION["id"])) {
        $query = "select * from moto natural join users where idUser = :idUser ";
        $params = Array("idUser" => $_SESSION["id"]);
        $st = PrepareExecute($query, $params);
        $datas = $st->fetch(PDO::FETCH_ASSOC);
        $motorcycleConsumption = $datas["consumption"];
    } else {
        $motorcycleConsumption = 0;
    }

    $array_response["MotorcycleConsumption"] = (int) $motorcycleConsumption;

    echo json_encode($array_response);
}

//add a route in database and return id
function CreateNewRoute($name, $containsHighway) {
    $id = CreateRoute($name, $_SESSION["id"], $containsHighway);
    echo json_encode($id);
}

//delete a route
function DeleteRoute($idRoute) {
    $query = "Delete from place where idRoute=:idRoute;";
    $params = array("idRoute" => $idRoute);
    PrepareExecute($query, $params);

    $query = "Delete from route where idRoute=:idRoute;";    
    PrepareExecute($query, $params);
    
    echo json_encode("Success");
}
