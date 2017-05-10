/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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
