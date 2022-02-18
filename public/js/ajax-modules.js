function AddModule2(id, bar){
    var Ruta = Routing.generate('AddMod');
    $.ajax({
        type: 'POST',
        url: Ruta,
        data: ({id: id}),
        async: true,
        dataType: "json",
        success: function (responseText) {
            console.log('done');

                $(".div").html( "<div>hola</div>");
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