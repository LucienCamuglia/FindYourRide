<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
$error = false;
$errorMsg = "";
if (isset($_GET["error"])) {
    if (!empty($_GET["error"])) {
        $error = true;
        $errorMsg = $_GET["error"];
    }
}
$value = false;
if (isset($_GET["values"])) {
    if (!empty($_GET["values"])) {
        $value = true;
        $values = json_decode($_GET["values"]);
    }
}
?>

<html>
    <head>
        <?php include './Includes/header.php' ?>
        <script src="./JS/Signin.js"></script>
        <title></title>
    </head>
    <body style="width: auto;">         
        <?php
        DisplayMenu(basename($_SERVER['PHP_SELF']));
        ?>
        <div class="col-sm-6 col-lg-offset-3 ">  
            <div class="alert alert-danger" <?php echo ($error) ? "" : "hidden"; ?>>
                <strong>Erreur !</strong> <label id='msg'><?php echo $errorMsg; ?></label>
            </div>
            <center>
                <h1>Sign in</h1>
                <form action="./Includes/signIn.php" method="post">
                    <div class="form-group row">
                        <label for="Username" class="col-sm-3 col-form-label" >Username  <span data-toggle="tooltip" title="min 5 char" class="glyphicon glyphicon-info-sign" id="InfoUsername"></span> </label>                         
                        <div class="col-sm-8"> 
                            <input class="form-control" type="text" name="username" id="Username" value="<?php
                            if ($value) {
                                if (isset($values->username)) {
                                    echo $values->username;
                                }
                            }
                            ?>">
                        </div>

                        <span class="glyphicon glyphicon-remove red" id="LogoUsername"></span>
                    </div>
                    <div class="form-group row">
                        <label for="Username" class="col-sm-3 col-form-label">email</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="email" name="email" id="email" value="<?php
                            if ($value) {
                                if (isset($values->email)) {
                                    echo $values->email;
                                }
                            }
                            ?>">
                        </div>                                                
                        <span class="glyphicon glyphicon-remove red" id="LogoEmail"></span>
                    </div>
                    <div class="form-group row">
                        <label for="Username" class="col-sm-3 col-form-label">password <span data-toggle="tooltip" title="8char min, 1upper, 1lower, 1Numeric" class="glyphicon glyphicon-info-sign" id="InfoPwd"></span> </label>
                        <div class="col-sm-8">
                            <input class="form-control" type="password" value="" name="password" id="password">
                        </div>

                        <span class="glyphicon glyphicon-remove red" id="Logopwd"></span>
                    </div>
                    <div class="form-group row">
                        <label for="Username" class="col-sm-3 col-form-label">password confirmation</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="password" value="" name="password2" id="password2">
                        </div>
                        <span class="glyphicon glyphicon-remove red" id="LogoPwd2"></span>
                    </div>

                    <div class="panel panel-primary">
                        <div class="panel-heading">Motorcycle</div>
                        <div class="panel-body">
                            <div class="form-group row">
                                <label for="Username" class="col-sm-3 col-form-label ">Brand</label>
                                <div class="col-sm-9">
                                    <select class='brand' name="brand">
                                        <?php
                                        if ($value) {
                                            if (isset($values->brand)) {
                                                echo "<option value=\"$values->brand\" >$values->brand</option>";
                                            }
                                        }
                                        ?>
                                        <option value="">Select...</option>
                                        <?php
                                        $brands = GetMotorcycleBrand();
                                        foreach ($brands as $brand) {
                                            echo "<option value='" . $brand . "'>" . $brand . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="Username" class="col-sm-3 col-form-label">Model </label>
                                <div class="col-sm-9">
                                    <select id="model" name="model">
                                        <?php
                                        if ($value) {
                                            if (isset($values->model)) {
                                                echo "<option value=\"$values->model\" >$values->model</option>";
                                            }
                                        }
                                        ?>

                                        <option  value="">Select a brand</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="Username" class="col-sm-3 col-form-label">Year </label>
                                <div class="col-sm-9">
                                    <select id="year" name="year" >
<?php
if ($value) {
    if (isset($values->year)) {
        echo "<option value=\"$values->year\" >$values->year</option>";
    }
}
?>
                                        <option  value="">Select a model</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4 col-lg-offset-4"> 
                            <input class="form-control btn btn-primary disabled " type="submit" name="submit" value="Sign in" id="Signin">
                        </div>
                    </div>
                </form>
            </center>
        </div>
          <?php include './Includes/footer.php'; ?>
    </body>    
</html>
