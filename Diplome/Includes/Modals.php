<?php

function includeConnexionModal() {
    ?>
    <div class="modal fade" id="modalConnexion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="alert alert-danger" id='msgCont' hidden="true">
                        <strong>Error !</strong> <label id='msg'></label>
                    </div>
                    <h5 class="modal-title" >Connexion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label  class="col-2 col-form-label">Username</label>
                        <div class="col-4">
                            <input class="form-control" type="text"  id="username" name="username" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label  class="col-2 col-form-label">Password</label>
                        <div class="col-4">
                            <input class="form-control" type="password"  id="pwd" name="password" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick='connexion([$("#username").val(), $("#pwd").val()])'>Connexion</button>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function AddMotorcycleModal() {
    ?>
    <div class="modal fade" id="modalMotorcycle" tabindex="-1" role="dialog" aria-labelledby="Modal motorcycle" aria-hidden="true">

        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="alert alert-danger" id='msgCont' hidden="true">
                        <strong>Erreur !</strong> <label id='msg'></label>
                    </div>
                    <h5 class="modal-title" >Add a Motorcycle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label  class="col-2 col-form-label">Brand</label>
                        <div class="col-4">
                            <input class="form-control" type="text"  id="AddBrand" name="Brand" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label  class="col-2 col-form-label">Model</label>
                        <div class="col-4">
                            <input class="form-control" type="text"  id="AddModel" name="Model" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label  class="col-2 col-form-label">Year</label>
                        <div class="col-4">
                            <select  id="Addyear" name="year" required>
                                <?php
                                for ($i = 1900; $i <= date("Y"); $i++) {
                                    echo "<option value=\"$i\">$i</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label  class="col-2 col-form-label">Consumption</label>
                        <div class="col-4">
                            <input class="form-control" type="number"  id="AddConsumption" name="Consumption" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label  class="col-2 col-form-label">Tiredness</label>
                        <div class="col-4">
                            <input class="form-control" type="number"  id="AddTiredness" name="Tiredness" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick='AddMotorcycle($("#AddBrand").val(), $("#AddModel").val(), $("#Addyear").val(), $("#AddConsumption").val(), $("#AddTiredness").val())'>Add</button>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function importerTrajetModal() {
    ?>
    <div class="modal fade" id="modalimport" tabindex="-1" role="dialog" aria-labelledby="modalimport" aria-hidden="true">
        <form method="post" action="./Includes/ImportGPX.php" enctype="multipart/form-data">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="alert alert-danger" id='msgCont' hidden="true">
                            <strong>Erreur !</strong> <label id='msg'></label>
                        </div>
                        <h5 class="modal-title" >Importer fichier .GPX</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label  class="col-2 col-form-label">Fichier</label>
                            <div class="col-4">
                                <input class="form-control" type="file"  id="GpxFile" name="GpxFile" required>
                            </div>
                            <label class="col-2 col-form-label" >This route contains highways </label> <input class="col-lg-offset-1" type="checkbox" name="highway">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary" value="Importer" name="import">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php
}

function confirmationDeleteMotorcycleModal($id, $brand, $model, $year) {
    ?>
    <div class="modal fade" id="modaldelete<?php echo $id?>" tabindex="-1" role="dialog" aria-labelledby="modaldelete<?php echo $id?>" aria-hidden="true">
        <form method="post" action="./Includes/deleteMotorcycle.php" >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                       
                        <h5 class="modal-title" >Delete a motorcycle</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label  class="col-2 col-form-label">Are you sure to delete :</label>
                            <label  class="col-2 col-form-label"><?php echo "$brand, $model, $year"?></label>  
                            <input hidden value="<?php echo $id?>" name="toDelete"/>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-danger" value="Yes" name="delete">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php
    }
    