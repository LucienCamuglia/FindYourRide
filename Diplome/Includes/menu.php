<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once './Includes/Modals.php';

function DisplayMenu($page) {
    includeConnexionModal();
    $erreur = false;
    if (isset($_SESSION['erreur'])) {
        $erreur = true;
        $erreurMsg = $_SESSION['erreur'];
        session_unset($_SESSION['erreur']);
    }
    ?>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">MotoSpot</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li <?php
                    if ($page == "Index.php") {
                        echo 'class="active"';
                    }
                    ?>><a href="./Index.php">Home <span class="sr-only">(current)</span></a></li>
                    <li <?php
                    if ($page == "map.php") {
                        echo 'class="active"';
                    }
                    ?>><a href="./map.php">Map</a></li>


                    <?php
                    if (isset($_SESSION["id"])) {
                        ?>                   

                        <li <?php
                if ($page == "itineraire.php") {
                    echo 'class="active"';
                }
                        ?>><a href="./itineraire.php">My routes</a></li>
                            <?php if ($_SESSION["role"] == 1) { ?>
                            <li <?php
                    if ($page == "manageUsers.php") {
                        echo 'class="active"';
                    }
                                ?>><a href="./manageUsers.php">Manage users</a></li>

                            <li <?php
                if ($page == "manageMoto.php") {
                    echo 'class="active"';
                }
                                ?>><a href="./manageMoto.php">Manage motorcycles</a></li>
                            <?php } ?>
                    </ul>


                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="./logout.php">Log Out</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a>Logged as <?php echo $_SESSION["username"]; ?></a></li>
                    </ul>
                    <?php
                } else {
                    ?>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li <?php
            if ($page == "Inscription.php") {
                echo 'class="active"';
            }
                    ?>><a href="./Inscription.php">Sign in</a></li>

                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li <?php
                if ($page == "connexion.php") {
                    echo 'class="active"';
                }
                    ?>  ><a href="#" data-toggle="modal" data-target="#modalConnexion" onclick='$("#msgCont").attr("Hidden", "true")'>Connection</a></li>

                    </ul>
                <?php } ?>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>

    <?php
}
