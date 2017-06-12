<!DOCTYPE html>
<?php 
/*
 * Author : Lucien Camuglia
 * Description : Managing users page
 * Date : April-june 2017
 * Version : 1.0 LC BaseVersion
 */
?>

<html>
    <head>
        <?php include './Includes/header.php' ?>
        <script src="./JS/ManageUsers.js"></script>
        <title></title>
    </head>
    <body>         
        <?php
        DisplayMenu(basename($_SERVER['PHP_SELF']));
        ?>

        <table class="table table-striped table-bordered table-responsive" cellspacing="0" id="">
            <thead>
                <tr>
                    <th rowspan="2" class="text-center valign">Username</th>
                    <th  rowspan="2"  class="text-center valign">E-Mail</th>
                    <th  colspan="4" class="text-center valign">Role</th>
                    <th colspan="3"  class="text-center valign">Motorcycle</th>
                    <th rowspan="2" class="text-center valign">Delete</th>
                </tr>
                <tr>
                    <th class="text-center valign">Designation</th>
                    <th  colspan="3" class="text-center valign">Action</th>
                    <th class="text-center valign">Brand</th>
                    <th class="text-center valign">Model</th>
                    <th class="text-center valign">Year</th>
                </tr>


            </thead>
            <tbody id="">
                <?php
                $users = getUsersNMotorcycle();
                foreach ($users as $user) {
                    echo"<tr>";
                    echo "<td>" . $user["Username"] . "</td>";
                    echo "<td>" . $user["email"] . "</td>";
                    $rank = $user["role"];
                    echo "<td > <span id=\"" . $user["idUser"] . "Role\" name=\"$rank\">";

                    switch ($rank) {
                        case 1 : echo "Administrator";
                            break;
                        case 2 : echo "User";
                            break;
                        case 3 : echo "Ban";
                            break;
                    }
                    echo "</span></td>";

                    echo "<td class=\"text-center no-border action-table ";
                    if ($rank == 1) {
                        echo "disabled";
                    }echo "\" style=\"width: 60px;\"><a name=\"" . $user["idUser"] . "\" class=\"UpgradeRole\"><span class=\" glyphicon glyphicon-chevron-up \" > upgrade</span></a> </td>";

                    echo "<td class=\"text-center no-border action-table ";
                    if ($rank != 1) {
                        echo "disabled";
                    }echo "\" style=\"width: 80px;\"><a name=\"" . $user["idUser"] . "\" class=\"DowngradeRole\"><span class=\" glyphicon glyphicon-chevron-down \" > downgrade</span></a></td>";
                    echo "<td class=\"text-center no-border action-table ";
                    if ($rank == 3) {
                        echo "disabled";
                    }echo " \" style=\"width: 50px;\" ><a name=\"" . $user["idUser"] . "\" class=\"BanRole\"><span class=\" glyphicon glyphicon-ban-circle \" > ban</span></a></td>";
                    echo "<td>" . $user["Brand"] . "</td>";
                    echo "<td>" . $user["model"] . "</td>";
                    echo "<td>" . substr($user["year"], 0, 4) . "</td>";
                    echo "<td> <span class=\"glyphicon glyphicon-remove red\" ></span></td>";
                }
                ?>
        
            </tbody>
            <tfoot>

            </tfoot>

        </table>
          <?php include './Includes/footer.php'; ?>
    </body>
</html>
