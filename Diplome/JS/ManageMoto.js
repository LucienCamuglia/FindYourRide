/*
 * Author : Lucien Camuglia
 * Description : managing motorcycles fonctions 
 * Date : April-june 2017
 * Version : 1.0 LC BaseVersion
 */

//create a new motorcycle
function AddMotorcycle(brand, model, year, consumption, tiredness) {

    $.ajax({
        url: './Includes/ajax.php',
        type: 'GET',
        data: {fonction: "AddMotorcycle", Brand: brand, Model: model, Year: year, Consumption: consumption, Tiredness: tiredness},
        dataType: "json",
        async: false,
        success: function(result) {
            if (result.error.status === true) {
            } else {
                window.location.reload(false);
            }

        }
    });
}


//load all motorcycles
function LoadMotorcycles(brand, model, year, consumption, tiredness) {
    $.ajax({
        url: './Includes/ajax.php',
        type: 'GET',
        data: {fonction: "GetMotorcycles", Brand: brand, Model: model, Year: year, Consumption: consumption, Tiredness: tiredness},
        dataType: "json",
        async: false,
        success: function(result) {
            $("#motorcylesDatas").html("");
            $.each(result, function(i, item) {

                $("#motorcylesDatas").append("<tr> <td>" + item.Brand + "</td>\n\
                                                 <td>" + item.model + "</td>\n\
                                                 <td>" + item.year.substring(0, 4) + "</td> \n\
                                                <td>" + item.consumption + "</td>\n\
                                                 <td>" + item.Tiredness + "</td>\n\
                                                <td> <span class=\"glyphicon glyphicon-remove red\" ></span> </td></tr>");

            });
        }
    });
}


$(document).ready(function() {
    //filters for motorcycles
    $("select").click(function() {
        LoadMotorcycles($("#brand").val(), $("#model").val(), $("#year").val() + "-01-01", $("#consumption").val(), $("#tiredness").val());
    });
});
