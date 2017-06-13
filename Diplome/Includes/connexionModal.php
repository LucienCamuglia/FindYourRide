<?php

function includeModal() {
    ?>
    <form method="post" action="./Includes/connexion.php?page=<?php echo $_SERVER['PHP_SELF'] ?>">
        <div class="modal fade" id="modalConnexion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
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
                        <input type="submit" class="btn btn-primary" name="connexion" value="Connexion">
                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php
}
?>

