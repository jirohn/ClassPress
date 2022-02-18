
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
    $('#content').load('http://localhost/anunciate/public/index.php/admin/dashboard', function() {
        /// can add another function here
    });
    $('#btn2').click(function(){
        $('#content').load("http://localhost/anunciate/public/index.php/admin/loaders #loader", function() {
            /// can add another function here
        });

        waitForElement("#content",function(){
            console.log("done");
            $('#content').load('http://localhost/anunciate/public/index.php/admin/usuarios', function() {
                /// can add another function here
            });
        });

    });
    $('#btn3').click(function(){
        $('#content').load('http://localhost/anunciate/public/index.php/admin/loaders #loader', function() {
            /// can add another function here
        });

        waitForElement("#content",function(){
            console.log("done");
            $('#content').load('http://localhost/anunciate/public/index.php/admin/anuncios', function() {
                /// can add another function here
            });
        });

    });

});
