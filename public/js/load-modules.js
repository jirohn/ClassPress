function waitForElement(elementPath, callBack){
    window.setTimeout(function(){
        if($(elementPath).length){
            callBack(elementPath, $(elementPath));
        }else{
            waitForElement(elementPath, callBack);
        }
    },500)
}
$(document).ready(function() { /// Wait till page is loaded
    $('#plugin_{{ col1 }}_content').load("{{ path( mod.modulo.funcion ) }}", function () {
        /// can add another function here

    }).fadeIn('slow');
});