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

    $('a#minhaconta').livequery('click',function(event){
        $("#modal").modal({
            cache:false,
            show: true,
            keyboard: false,
            backdrop: 'static',
            remote: 'views/administrador/usuario/view.php'
        });

        //get
        app.util.getjson({
            url : "/controller/administrador/usuario/get",
            method : 'POST',
            contentType : "application/json",
            success: function(response){
                if(response.id){
                    //set
                    $('form#formMinhaconta input#nome').val(response.nome);
                    $('form#formMinhaconta input#sobrenome').val(response.sobrenome);
                    $('form#formMinhaconta input#email').val(response.email);
                    $('form#formMinhaconta input#desde').val(response.created_at);
                }
            },
            error : onError
        });
        return false;
    });

    $('a#mudarsenha').livequery('click',function(event){
        $("#modal").modal({
            cache:false,
            show: true,
            keyboard: false,
            backdrop: 'static',
            remote: 'views/administrador/usuario/password.php'
        });
        return false;
    });

    function onError(response) {
      console.log(response);
    }

});