<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

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

                    echo "<td class=\"text-center no-border ";
                    if ($rank == 1) {
                        echo "disabled";
                    }echo "\" style=\"width: 60px;\"><a name=\"" . $user["idUser"] . "\" class=\"UpgradeRole\"><span class=\" glyphicon glyphicon-chevron-up \" > upgrade</span></a> </td>";

                    echo "<td class=\"text-center no-border ";
                    if ($rank != 1) {
                        echo "disabled";
                    }echo "\" style=\"width: 80px;\"><a name=\"" . $user["idUser"] . "\" class=\"DowngradeRole\"><span class=\" glyphicon glyphicon-chevron-down \" > downgrade</span></a></td>";
                    echo "<td class=\"text-center no-border ";
                    if ($rank == 3) {
                        echo "disabled";
                    }echo " \" style=\"width: 50px;\" ><a name=\"" . $user["idUser"] . "\" class=\"BanRole\"><span class=\" glyphicon glyphicon-ban-circle \" > ban</span></a></td>";
                    echo "<td>" . $user["Brand"] . "</td>";
                    echo "<td>" . $user["model"] . "</td>";
                    echo "<td>" . substr($user["year"], 0, 4) . "</td>";
                    echo "<td> <span class=\"glyphicon glyphicon-remove red\" ></span></td>";
                }
                ?>
                <!--<tr>
                    <td>admin</td>
                    <td>admin@cfpt.c</td>
                    <td>administrator </td>
                    <td class="text-center" style="width: 60px;"><span class=" glyphicon glyphicon-chevron-up " > upgrade</span> </td>
                    <td class="text-center" style="width: 80px;"><span class=" glyphicon glyphicon-chevron-down " > downgrade</span></td>
                    <td class="text-center" style="width: 50px;" ><span class=" glyphicon glyphicon-ban-circle " > block</span></td>
                    <td>Kawasaki</td>
                    <td>z750</td>
                    <td>2014</td>
                </tr>-->
            </tbody>
            <tfoot>

            </tfoot>

        </table>
    </body>
</html>
