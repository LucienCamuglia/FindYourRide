<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<html>
    <head>
        <?php include './Includes/header.php' ?>
        <script src="./JS/ManageMoto.js"></script>
        <title></title>
    </head>
    <body>         
        <?php
        DisplayMenu(basename($_SERVER['PHP_SELF']));
        AddMotorcycleModal();
        $motorcyles = GetAllMotorcycles();
        foreach ($motorcyles as $motorcycle){
            confirmationDeleteMotorcycleModal($motorcycle["idMoto"],$motorcycle["Brand"],$motorcycle["model"],substr($motorcycle["year"],0,4));
        }
        ?>

        <table class="table table-striped table-bordered" cellspacing="0" id="MotorcycleTable">
            <thead>
                <tr>
                    <th>Brand <select id="brand" ><option value="%">All</option> <?php
                            $brands = GetMotorcycleBrand();
                            foreach ($brands as $brand) {
                                echo "<option value='" . $brand . "'>" . $brand . "</option>";
                            }
                            ?></select> </th>
                    <th>Model <select  id="model"><option value="%">All</option><?php
                            $models = GetAllMotorcycleModel();
                            foreach ($models as $model) {
                                echo "<option value='" . $model . "'>" . $model . "</option>";
                            }
                            ?></select></th>
                    <th>Year <select  id="year"><option value="%">All</option><?php
                            $years = GetAllMotorcycleYear();
                            foreach ($years as $year) {
                                echo "<option value='" . $year . "'>" . $year . "</option>";
                            }
                            ?></select></th>
                    <th>Consumption (l/100km) <select  id="consumption" ><option value="%">All</option><?php
                            $consumptions = GetAllMotorcycleConsumption();
                            foreach ($consumptions as $consumption) {
                                echo "<option value='" . $consumption . "'>" . $consumption . "</option>";
                            }
                            ?></select></th>
                    <th>Tiredness <select  id="tiredness"><option value="%">All</option><?php
                            $tirednesses = GetAllMotorcycleTiredness();
                            foreach ($tirednesses as $tiredness) {
                                echo "<option value='" . $tiredness . "'>" . $tiredness . "</option>";
                            }
                            ?></select></th>
                    <th>Delete</th>
                </tr>

            </thead>
            <tbody id="motorcylesDatas">
                <?php              
                
                foreach ($motorcyles as $motorcyle) {
                    echo "<tr>";
                    echo "<td>";
                    echo $motorcyle["Brand"];
                    echo "</td>";
                     echo "<td>";
                    echo $motorcyle["model"];
                    echo "</td>";
                     echo "<td>";
                    echo substr($motorcyle["year"],0,4);
                    echo "</td>";
                     echo "<td>";
                    echo $motorcyle["consumption"];
                    echo "</td>";
                     echo "<td>";
                    echo $motorcyle["Tiredness"];
                    echo "</td>";
                    echo "<td>";
                    echo "<span class=\"glyphicon glyphicon-remove red\"  data-toggle=\"modal\" data-target=\"#modaldelete".$motorcyle["idMoto"]."\" ></span>";                    
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
            <tfoot>
                <tr><td colspan="6" ><a href="#" data-toggle="modal" data-target="#modalMotorcycle" onclick='$("#msgCont").attr("Hidden", "true")'> + Add a Motorcycle</a></td></tr>
            </tfoot>

        </table>
          <?php include './Includes/footer.php'; ?>
    </body>
</html>
