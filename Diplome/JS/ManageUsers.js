/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function banUser(idUser){
   /*  $.ajax({
        url: './Includes/ajax.php',
        type: 'GET',
        data: {fonction: "UpdateUserRole", idUser: idUser, Role : 3},
        dataType: "json",
        async: false,
        success: function(result) {
         
        }
    });*/
}

function UpgradeUser(idUser){
    
}

function DowngradeUser(idUser){
    
}

$(document).ready(function() {
    $(".BanRole").click(function(){
        banUser($(this).attr('name'));
    });
    
     $(".UpgradeRole").click(function(){
        UpgradeUser($(this).attr('name'));
    });
    
     $(".DowngradeRole").click(function(){
        DowngradeUser($(this).attr('name'));
    });
});
