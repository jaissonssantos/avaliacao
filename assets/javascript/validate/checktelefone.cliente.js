$.validator.addMethod( "checktelefone", function(value){

	var response = {};

	var params = {telefone: value};
    	params = JSON.stringify(params);

    response = $.ajax({ 
        async: false, 
        url: "/controller/guest/cliente/checktelefone",
        method: 'POST',
        contentType: "application/json",
        data: params,
     }); 

    response = JSON.parse(response.responseText);

    if(response.success){
		return false;
    }else{
    	return true;
    }

}, "E-mail already in use" );
