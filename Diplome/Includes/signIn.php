<?php

require_once './functions.php';

if (isset($_POST["submit"])) {
    if (isset($_POST["username"])&&!empty($_POST["username"])) {
        $values["username"] = $_POST["username"];
        if (isset($_POST["email"])&&!empty($_POST["email"])) {
            $values["email"] = $_POST["email"];
            if (isset($_POST["password"])&&!empty($_POST["password"])) {
                $values["password"] = sha1($_POST["password"]);
                if (isset($_POST["password2"])&&!empty($_POST["password2"])) {
                    $values["password2"] = sha1($_POST["password2"]);
                    if (isset($_POST["brand"])&&!empty($_POST["brand"])) {
                        $values["brand"] = $_POST["brand"];
                        if (isset($_POST["model"])&&!empty($_POST["model"])) {
                            $values["model"] = $_POST["model"];
                            if (isset($_POST["year"])&&!empty($_POST["year"])) {
                                $values["year"] = $_POST["year"];

                                if ($values["password"] == $values["password2"]) {
                                    if(strlen($values["username"])>=5){
                                        signin(json_encode($values));
                                          header("location: ../Index.php?validation=Inscription%20réussie");
                                    }else {
                                    redirect("Username length minimum 5 characters", $values);
                                }
                                } else {
                                    redirect("Password aren't the same", $values);
                                }
                            } else {
                                redirect("Year is required", $values);
                            }
                        } else {
                            redirect("model is required", $values);
                        }
                    } else {
                        redirect("brand is required", $values);
                    }
                } else {
                    redirect("Password confirmation is required", $values);
                }
            } else {
                redirect("Password is required", $values);
            }
        } else {
            redirect("Email is required", $values);
        }
    } else {
        redirect("Username is required");
    }
}else {
        redirect();
    }

exit();

function redirect($error = NULL, $values = NULL) {
    $values = json_encode($values);
    header("location: ../Inscription.php?error=$error&values=$values");
}
?>
