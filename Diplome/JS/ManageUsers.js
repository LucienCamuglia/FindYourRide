/*
 * Author : Lucien Camuglia
 * Description : managing users fonctions
 * Date : April-june 2017
 * Version : 1.0 LC BaseVersion
 */

//update the user role
function ModifyUser(idUser, role) {
    $.ajax({
        url: './Includes/ajax.php',
        type: 'GET',
        data: {fonction: "UpdateUserRole", idUser: idUser, Role: role},
        dataType: "json",
        success: function(result) {
            $("#" + idUser + "Role").html(result.datas.NewRole);
            location.reload();
        }
    });
}


$(document).ready(function() {
    $(".BanRole").click(function() {
        var idUser = $(this).attr('name');
        ModifyUser(idUser, 3);
    });

    $(".UpgradeRole").click(function() {
        var idUser = $(this).attr('name');
        var role = $("#" + idUser + "Role").attr('name');
        ModifyUser(idUser, --role);
    });

    $(".DowngradeRole").click(function() {
        var idUser = $(this).attr('name');
        var role = $("#" + idUser + "Role").attr('name');
        ModifyUser(idUser,++role);
    });
});
