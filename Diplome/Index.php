<!DOCTYPE html>
<html>
<?php
/*
 * Author : Lucien Camuglia
 * Description : index page
 * Date : April-june 2017
 * Version : 1.0 LC BaseVersion
 */
include './Includes/header.php'
?>

    <body>         
        <?php
        DisplayMenu(basename($_SERVER['PHP_SELF']));
        ?>
        <?php include './Includes/footer.php'; ?>

        <div class="col-lg-6 col-lg-offset-3">
        <p>The website was made for my Technician diploma, it allows sharing itineraries between motorcycle enthusiasts. The site is set-up with HTML/PHP/CSS/AJAX and Google’s API. It allows users to create trips, to share and modify them.</p>


        <p>Different criteria can be defined by the user to create the itinerary:</p>
        <ul>
            <li> Travel Time </li>
            <li> Type of road </li>
            <li> Change in altitude, etc.</li>
        </ul>



        <p>This website gives the user diverse informations. One example could be the theoretical consumption of their motorcycle for the ride or travel time. The rider can also upload or download files directly from his GPS.</p>
        </div>
    </body>

</html>
