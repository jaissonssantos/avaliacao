$(document).ready(function(){
	$('input#cpf').on('keydown',function(e) {
		/* Allow backspace, delete, tab, esc e enter */
		if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
		/* Allow CTRL+A */
		(event.keyCode == 65 && event.ctrlKey === true) || 
		/* Allow CTRL+C */
		(event.keyCode == 67 && event.ctrlKey === true) || 
		/* Allow CTRL+X */
		(event.keyCode == 88 && event.ctrlKey === true) || 
		/* Allow CTRL+V */
		(event.keyCode == 86 && event.ctrlKey === true) || 
		/* Allow Command+A (Mac) */
		(event.keyCode == 65 && event.metaKey === true) || 
		/* Allow Command+C (Mac) */
		(event.keyCode == 67 && event.metaKey === true) || 
		/* Allow Command+X (Mac) */
		(event.keyCode == 88 && event.metaKey === true) || 
		/* Allow Command+V (Mac) */
		(event.keyCode == 86 && event.metaKey === true) || 
		/* Allow home, end, left e right keys */
		(event.keyCode >= 35 && event.keyCode <= 39)){
			return;	
		}else{
			if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )){
				event.preventDefault(); 
			}   
		}
	});
});

/*   
  @brief Cria uma paginação
  @param limit(int) - item por página
  @param page(int) - página atual
  @param total(int) - total de itens
  @return pagination(text) - html da paginação
*/
function paginator(limit,page,total){
  //pagination
  var pagination = '';
  var total_pages = Math.ceil(parseInt(total)/limit);
  var right_link = page+3;

  if(page > 1){
      pagination +=   '<li>'+
                          '<a href="#" data-page="1">'+
                              '<span>Primeiro</span>'+
                          '</a>'+
                      '</li>'+
                      '<li>'+
                          '<a href="#" data-page="'+(page-1)+'">'+
                              '<span>Anterior</span>'+
                          '</a>'+
                      '</li>';
      for(var i=(page-2);i<page;i++){
          if(i>0)
              pagination +=   '<li>'+
                                  '<a href="#" data-page="'+i+'">'+
                                      '<span>'+i+'</span>'+
                                  '</a>'+
                              '</li>';
      }   
  }

  if(page == total_pages){
      pagination +=   '<li class="active">'+
                          '<a href="#" data-page="'+page+'">'+
                              '<span>'+page+'</span>'+
                          '</a>'+
                      '</li>';
  }else{
      pagination +=   '<li class="active">'+
                          '<a href="#" data-page="'+page+'">'+
                              '<span>'+page+'</span>'+
                          '</a>'+
                      '</li>';
  }

  for(var i=(page+1);i<=right_link;i++){
      if(i <= total_pages){
          pagination +=   '<li>'+
                              '<a href="#" data-page="'+i+'">'+
                                  '<span>'+i+'</span>'+
                              '</a>'+
                          '</li>';
      }
  }

  if(page < total_pages){
      pagination +=   '<li>'+
                          '<a href="#" data-page="'+(page+1)+'">'+
                              '<span>Próximo</span>'+
                          '</a>'+
                      '</li>'+
                      '<li>'+
                          '<a href="#" data-page="'+total_pages+'">'+
                              '<span>Último</span>'+
                          '</a>'+
                      '</li>';
  }
  return pagination;
}

/*   
	@brief Converte uma string em formato moeda para float
  @param valor(string) - o valor em moeda
  @return valor(float) - o valor em float
*/
function moneyToFloat(valor){
	if(valor === ""){
 		valor =  0;
	}else{
	 	valor = valor.replace(".","");
	 	valor = valor.replace(",",".");
	 	valor = parseFloat(valor);
	}
	return valor;
}

/*   
	@brief Converte um valor em formato float para uma string em formato moeda
  @param valor(float) - o valor float
  @return valor(string) - o valor em moeda
*/
function floatToMoney(valor,mascara){
  var inteiro = null, decimal = null, c = null, j = null;
  var aux = new Array();
  valor = ""+valor;
  c = valor.indexOf(".",0);
  //encontrou o ponto na string
  if(c > 0){
     //separa as partes em inteiro e decimal
     inteiro = valor.substring(0,c);
     decimal = valor.substring(c+1,valor.length);
  }else{
     inteiro = valor;
  }
  
  //pega a parte inteiro de 3 em 3 partes
  for (j = inteiro.length, c = 0; j > 0; j-=3, c++){
     aux[c]=inteiro.substring(j-3,j);
  }
  
  //percorre a string acrescentando os pontos
  inteiro = "";
  for(c = aux.length-1; c >= 0; c--){
     inteiro += aux[c]+'.';
  }

  //retirando o ultimo ponto e finalizando a parte inteiro
  inteiro = inteiro.substring(0,inteiro.length-1);
  decimal = parseInt(decimal);
  if(isNaN(decimal)){
     decimal = "00";
  }else{
     decimal = ""+decimal;
     if(decimal.length === 1){
        decimal = decimal+"0";
     }
  }
  valor = mascara+' '+inteiro+","+decimal;  
  return valor;
}

