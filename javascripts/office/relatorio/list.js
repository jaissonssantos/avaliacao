$(document).ready(function(){

    function onError(response) {
      console.log(response);
    }

    function list(){
        var params = {hash: $('#idq').val()};
        params = JSON.stringify(params);

        //alert("chamou");
        
        //list
        app.util.getjson({
            url : "/controller/office/relatorio/list",
            method : 'POST',
            data: params,
            contentType : "application/json",
            success: function(response){
                //alert(response.pergunta[0].titulo);
                var html = '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="white-box">';
                html += '<h4 class="page-title">'+response.titulo+'</h4><h3 class="box-title">Resultados da Pesquisa</h3></div></div>';
                html +='<div class="row">';
                for (var i=0;i<response.pergunta.length;i++) {
                    //falta fazer o de respostas curtas
                    if(response.pergunta[i].tipo != 1){
                        html +='<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">';
                        html +='    <div class="white-box">';
                        html +='        <h3 class="box-title">'+response.pergunta[i].titulo+'</h3>';
                        html +='        <div class="sl-item">';
                        html +='                    <div class="sl-right">';
                        for (var j=0;j<response.pergunta[i].resposta.length;j++) {
                            html +='<div><a href="#">'+response.pergunta[i].resposta[j].titulo+'</a> <span class="pull-right label-info label">'+response.pergunta[i].resposta[j].qtd+'</span></div>'; 
                        }
                        html +='            </div>';
                        html +='        </div>';
                        
                        html +='<button class="btn btn-success btn-rounded waves-effect waves-light m-t-20">Ver Gráfico</button>';
                        html +='    </div>';
                        html +='</div>';
                    }
                }

                html +='</div>';
                $("#resultados").html(html);
            },
            error : onError
        });
    }

    list();

    //alert("Chanou");

});