function MeGusta(id){
    var Ruta = Routing.generate('Likes');
    $.ajax({
        type: 'POST',
        url: Ruta,
        data: ({id: id}),
        async: true,
        dataType: "json",
        success: function (responseText) {

            $('#foto').html(responseText);
        }
    });
}
function darAdmin(id){


    var Ruta = Routing.generate('DarAdmin');

    $.ajax({
        type: 'POST',
        data: ({id: id}),
        url: Ruta,
        async: true,
        dataType: "json",
        success: function (responseText) {
            
        }
    });
}
function reloadUsers(){


    var Ruta = Routing.generate('admin_panel');

    $.ajax({
        type: 'GET',
        url: Ruta,
        async: true,
        dataType: "json",
        data: {index: 'add'},
        success: function (responseText) {

        }
    });


}
function configName(){


    var Ruta = Routing.generate('ConfigNombre');

    $.ajax({
        type: 'GET',
        url: Ruta,
        async: true,
        dataType: "json",
        data: {index: 'add'},
        success: function (responseText) {

        }
    });

}
function waitForElement(elementPath, callBack){
    window.setTimeout(function(){
        if($(elementPath).length){
            callBack(elementPath, $(elementPath));
        }else{
            waitForElement(elementPath, callBack);
        }
    },500)
}

