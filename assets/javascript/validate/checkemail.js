$.validator.addMethod( "checkemail", function(value){

	var response = {};

	var params = {email: value};
    	params = JSON.stringify(params);

    response = $.ajax({ 
        async: false, 
        url: "/controller/guest/usuario/checkemail",
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
