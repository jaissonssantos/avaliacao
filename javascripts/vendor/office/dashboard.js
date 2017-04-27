$(document).ready(function(){

    //sair da conta
    $('a#sair').livequery('click',function(event){
        app.util.getjson({
            url : "/controller/guest/usuario/logout",
            method : 'POST',
            contentType : "application/json",
            success: function(response){
                if(response.success)
                    window.location.href = "/login";
            },
            error : onError
        });
        return false;
	});

	function onError(response) {
      console.log(response);
    }

});