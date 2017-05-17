<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Connexion à la base de données
 * @staticvar PDO $pdo  objet de connexion
 * @return PDO  connexion à la base
 */
function connexionDb() {

    try {
        //variables contenant les informations de connexion ainsi que la DB
        $serveur = '127.0.0.1';
        $pseudo = 'root';
        $pwd = '';
        $db = 'findyourride';

        static $pdo = null;

        if ($pdo === NULL) {
            // Connexion à la base.
            $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
            $pdo = new PDO("mysql:host=$serveur;dbname=$db", $pseudo, $pwd, $pdo_options);
            $pdo->exec("Set Character set UTF8");
        }
        return $pdo;
    } catch (Exception $exc) {
        echo $exc->getTraceAsString();
    }
}

$pdo = connexionDb();

/**
 * Prépare et éxécute une requête
 * @param string $query requête a ééxécuter
 * @param tableau $params  éventuel paramètre pour la requête
 * @return PDOStatement
 */
Function PrepareExecute($query, $params = NULL) {
    global $pdo;
// Préparation de la requête SQL.
    $st = $pdo->prepare($query);
// Execution de la requête SQL.
    $st->execute($params);
    return $st;
}

/**
 * Connecte un utilisateur
 * @param String $User Nom d'utilisateur
 * @param Sha1_String $Password Mot de passe cripté
 * @Return Boolean Vrai si la connexion a marché
 */
function ConnexionUser($User, $Password) {
    global $pdo;

    $query = 'SELECT idUser, Username, Password, role FROM users ';

    $st = PrepareExecute($query);
    $connecter = false;

    while ($data = $st->fetch(PDO::FETCH_ASSOC)) {
        if ($data['Username'] == $User && $data['Password'] == $Password) {
            $id = $data['idUser'];
            $username = $data["Username"];
            $role = $data["role"];

            $connecter = true;
            break;
        }
    }
    if ($connecter) {
        $_SESSION["role"] = $role;
        $_SESSION["id"] = $id;
        $_SESSION["username"] = $username;
        return true;
    } else {
        return false;
    }
}

/**
 * 
 * @param String $Username nom d'utilisateur
 * @param String $password mot de passe crypté en sha1
 * @return boolean true si ajouté et false si il existe déjà
 */
function InscriptionUser($Username, $password) {
    global $pdo;
    $query = "Select Username FROM users where Username=:username;";
    $params = array('username' => $Username);
    $st = PrepareExecute($query, $params);
    while ($data = $st->fetch(PDO::FETCH_ASSOC)) {
        return false;
    }

    $query = "Insert Into Users (Username,Password) values (:username,:password);";
    $params = array(
        'username' => $Username,
        'password' => $password
    );
    $st = PrepareExecute($query, $params);
    return true;
}

function GetMotorcycleBrand() {
    $query = "select distinct Brand from moto";
    $st = PrepareExecute($query);
    while ($data = $st->fetch(PDO::FETCH_ASSOC)) {
        $array_response[] = $data["Brand"];
    }
    return $array_response;
}

function GetAllMotorcycleModel() {
    $query = "select distinct model from moto order by model";
    $st = PrepareExecute($query);
    while ($data = $st->fetch(PDO::FETCH_ASSOC)) {
        $array_response[] = $data["model"];
    }
    return $array_response;
}

function GetAllMotorcycleYear() {
    $query = "select distinct year from moto order by year";
    $st = PrepareExecute($query);
    while ($data = $st->fetch(PDO::FETCH_ASSOC)) {
        $array_response[] = substr($data["year"], 0, 4);
    }
    return $array_response;
}

function GetAllMotorcycleConsumption() {
    $query = "select distinct consumption from moto order by consumption";
    $st = PrepareExecute($query);
    while ($data = $st->fetch(PDO::FETCH_ASSOC)) {
        $array_response[] = $data["consumption"];
    }
    return $array_response;
}

function GetAllMotorcycleTiredness() {
    $query = "select distinct Tiredness from moto order by Tiredness";
    $st = PrepareExecute($query);
    while ($data = $st->fetch(PDO::FETCH_ASSOC)) {
        $array_response[] = $data["Tiredness"];
    }
    return $array_response;
}

function GetAllMotorcycles() {
    $query = "select * from moto order by Brand";
    $st = PrepareExecute($query);
    while ($data = $st->fetch(PDO::FETCH_ASSOC)) {
        $array_response[] = $data;
    }
    return $array_response;
}

function signin($values) {

    $values = json_decode($values);

    $query = "Select Username FROM users where Username=:username;";
    $params = array('username' => $values->username);
    $st = PrepareExecute($query, $params);
    while ($data = $st->fetch(PDO::FETCH_ASSOC)) {
        return false;
    }



    $query = "SELECT idMoto FROM moto WHERE Brand=:brand AND model=:model AND year=:year";
    $params = array(
        'brand' => $values->brand,
        'model' => $values->model,
        'year' => $values->year . "-01-01"
    );
    $st = PrepareExecute($query, $params);
    $idMoto = $st->fetch(PDO::FETCH_ASSOC)["idMoto"];
    $query = "INSERT INTO users(Username, Password, email, idMoto, role) VALUES (:username,:password,:email,:idmoto,2)";
    $params = array(
        'username' => $values->username,
        'password' => $values->password,
        'email' => $values->email,
        'idmoto' => $idMoto
    );
    $st = PrepareExecute($query, $params);

    global $pdo;
    return $pdo->lastInsertId();
}

function GetRoutes() {
    $query = "select * from route order by RouteName";
    $st = PrepareExecute($query);
    while ($data = $st->fetch(PDO::FETCH_ASSOC)) {
        $array_response[] = $data;
    }
    return $array_response;
}

function CreateRoute($name, $iduser) {
    $query = "INSERT INTO route(RouteName, idUser) VALUES (:name,:user)";
    $params = array(
        'name' => $name,
        'user' => $iduser
    );
    $st = PrepareExecute($query, $params);
    global $pdo;
    return $pdo->lastInsertId();
}

function deleatePlaces($idRoute) {
    $query = "DELETE FROM `place` WHERE idRoute = :idRoute";
    $params = array(
        'idRoute' => $idRoute
    );
    $st = PrepareExecute($query, $params);
}

function addPlaceToRoute($lat, $lon, $position, $idRoute) {
    $query = "INSERT INTO place(latitude, longitude, position, idRoute) VALUES (:lat,:lon,:pos,:route)";
    $params = array(
        'lat' => $lat,
        'lon' => $lon,
        'pos' => $position,
        'route' => $idRoute
    );
    $st = PrepareExecute($query, $params);
}

function Gpx2Sql($file, $iduser) {
    $dom = new DOMDocument();
    $dom->load($file);
    $names = $dom->getElementsByTagName("name");
    $name = $names->item(0)->nodeValue;
    $trackpoint = $dom->getElementsByTagName("trkpt");
    if ($trackpoint == NULL) {
        $trackpoint = $dom->getElementsByTagName("rtept");
    }
    $idRoute = CreateRoute($name, $iduser);
    $position = 0;
    foreach ($trackpoint as $point) {
        $lat = $point->getAttribute("lat");
        $lon = $point->getAttribute("lon");
        addPlaceToRoute($lat, $lon, $position++, $idRoute);
    }
    return $idRoute;
}

function Path2Gpx($name, $path) {
    $path = json_decode($path);
    $file = $name . ".gpx";
    if (file_exists($file)) {
        unlink($file);
    }
    $handle = fopen($file, "w");
    $string = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
    fwrite($handle, $string);
    $string = "<gpx version=\"1.0\" creator=\"FindYourRide.org\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns=\"http://www.topografix.com/GPX/1/0\" xsi:schemaLocation=\"http://www.topografix.com/GPX/1/0 http://www.topografix.com/GPX/1/0/gpx.xsd\">";
    fwrite($handle, $string);
    $string = "<trk> <name>$name</name> <trkseg>";
    fwrite($handle, $string);
    foreach ($path as $point) {
        $string = "<trkpt lat=\"" . $point->lat . "\" lon=\"" . $point->lng . "\"> </trkpt>";
        fwrite($handle, $string);
    }

    $string = "</trkseg> </trk> </gpx>";
    fwrite($handle, $string);
    fclose($handle);
    return $file;
}

function deleteMotorcycle($id) {
    $query = "Delete from moto where idMoto=:id";
    $params = array(
        'id' => $id
    );
    $st = PrepareExecute($query, $params);
}

function getUsersNMotorcycle() {
    $array_response = [];
    $query = "Select * from users natural join moto;";
    $st = PrepareExecute($query);
    while ($data = $st->fetch(PDO::FETCH_ASSOC)) {
        $array_response[] = $data;
    }
    return $array_response;
}

function getUserRoleById($id) {
    $query = "Select role from users where idUser=:id;";
    $params = array(
        'id' => $id
    );
    $st = PrepareExecute($query, $params);
    $data = $st->fetch(PDO::FETCH_ASSOC);
    return $data["role"];
}

?>