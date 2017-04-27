$.validator.addMethod( "checkcpfcnpj", function(value){

    // Removing special characters from value
    value = value.replace( /([~!@#$%^&*()_+=`{}\[\]\-|\\:;'<>,.\/? ])+/g, "" );
    
	var response = {};

	var params = {cpfcnpj: value};
    	params = JSON.stringify(params);

    response = $.ajax({ 
        async: false, 
        url: "/controller/guest/estabelecimento/checkcpfcnpj",
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

}, "CPF already in use" );
