/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var passwordRegex = new RegExp(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/i); //8char 1upper 1 lower 1 number
var emailRegex = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/i); //found on http://stackoverflow.com/questions/2507030/email-validation-using-jquery

function connexion(datas) {
    $.ajax({
        url: './Includes/connexion.php',
        type: 'POST',
        data: {username: datas[0], password: datas[1]},
        dataType: "json",
        success: function(result) {
            if (result.status === "success") {
                location.reload();
            } else {
                $("#msgCont").removeAttr("hidden");
                $("#msg").html(result.message);
            }
        }
    });
}

function LoadModelFromBrand(brand) {
    $.ajax({
        url: './Includes/ajax.php',
        type: 'GET',
        data: {fonction: "GetModel", Brand: brand},
        dataType: "json",
        success: function(result) {
            $("#model").html("");
            $("#year").html("<option value=\"\">Select a model</option>");
            $("#model").append("<option value=\"\">Select...</option>");
            $.each(result, function(i, item) {
                $("#model").append("<option value=" + item + ">" + item + "</option>");
            });
        }
    });
}

function LoadYearFromModel(brand, model) {
    $.ajax({
        url: './Includes/ajax.php',
        type: 'GET',
        data: {fonction: "GetYear", Brand: brand, Model: model},
        dataType: "json",
        success: function(result) {
            $("#year").html("");
            $("#year").append("<option value=\"\">Select...</option>");
            $.each(result, function(i, item) {
                $("#year").append("<option value=" + item + ">" + item + "</option>");
            });
        }
    });


}

function UserNameExists(username) {
    exist = false;
    $.ajax({
        url: './Includes/ajax.php',
        type: 'GET',
        data: {fonction: "UserExists", Username: username},
        dataType: "json",
        async: false,
        timeout: 30000,
        success: function(result) {
            if (result.exist) {
                exist = true;
                console.log("existe");
            }
        }
    });
    return exist;
}

function checkForEnabled() {
    $("#Signin").removeClass("disabled");
    if ($("#LogoUsername").hasClass("red")) {
        $("#Signin").addClass("disabled");
    }
    if ($("#Logopwd").hasClass("red")) {
        $("#Signin").addClass("disabled");
    }
    if ($("#LogoEmail").hasClass("red")) {
        $("#Signin").addClass("disabled");
    }
    if ($("#LogoPwd2").hasClass("red")) {
        $("#Signin").addClass("disabled");
    }
    if ($(".brand").val() === "") {
        $("#Signin").addClass("disabled");
    }
    if ($("#model").val() === "") {
        $("#Signin").addClass("disabled");
    }
    if ($("#year").val() === "") {
        $("#Signin").addClass("disabled");
    }
}

$(document).ready(function() {        

    $('[data-toggle="tooltip"]').tooltip();

    $(".brand").click(function() {
        LoadModelFromBrand($(this).val());
        checkForEnabled();
    });

    $("#model").click(function() {
        LoadYearFromModel($(".brand").val(), $(this).val());
        checkForEnabled();
    });
    
    $("#year").click(function(){
        checkForEnabled();
    });

    $('#Username').on('input', function(e) {
        text = $("#Username").val();
        $("#LogoUsername").removeClass("green");
        $("#LogoUsername").removeClass("glyphicon-ok");
        $("#LogoUsername").addClass("red");
        $("#LogoUsername").addClass("glyphicon-remove");
        if (text.length >= 5) {
            exist = UserNameExists(text);
            console.log(exist);
            if (!exist) {
                $("#LogoUsername").removeClass("red");
                $("#LogoUsername").removeClass("glyphicon-remove");
                $("#LogoUsername").addClass("green");
                $("#LogoUsername").addClass("glyphicon-ok");
            }
        }
        checkForEnabled();
    });

    $('#password').on('input', function(e) {
        $("#Logopwd").removeClass("green");
        $("#Logopwd").removeClass("glyphicon-ok");
        $("#Logopwd").addClass("red");
        $("#Logopwd").addClass("glyphicon-remove");
        if (passwordRegex.test($('#password').val())) {
            $("#Logopwd").removeClass("red");
            $("#Logopwd").removeClass("glyphicon-remove");
            $("#Logopwd").addClass("green");
            $("#Logopwd").addClass("glyphicon-ok");
        }
        checkForEnabled();
    });

    $('#email').on('input', function(e) {
        $("#LogoEmail").removeClass("green");
        $("#LogoEmail").removeClass("glyphicon-ok");
        $("#LogoEmail").addClass("red");
        $("#LogoEmail").addClass("glyphicon-remove");
        if (emailRegex.test($('#email').val())) {
            $("#LogoEmail").removeClass("red");
            $("#LogoEmail").removeClass("glyphicon-remove");
            $("#LogoEmail").addClass("green");
            $("#LogoEmail").addClass("glyphicon-ok");
        }
        checkForEnabled();
    });

    $('#password2').on('input', function(e) {
        $("#LogoPwd2").removeClass("green");
        $("#LogoPwd2").removeClass("glyphicon-ok");
        $("#LogoPwd2").addClass("red");
        $("#LogoPwd2").addClass("glyphicon-remove");
        if ($('#password2').val() === $('#password').val()) {
            $("#LogoPwd2").removeClass("red");
            $("#LogoPwd2").removeClass("glyphicon-remove");
            $("#LogoPwd2").addClass("green");
            $("#LogoPwd2").addClass("glyphicon-ok");
        }
        checkForEnabled();
    });

});