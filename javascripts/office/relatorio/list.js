$(document).ready(function(){

    function onError(response) {
      console.log(response);
    }

    var cont = 0;

    var cores = ['#696969', '#FF4500', '#00BFFF', '#008000', '#FF0000', '#6A5ACD', '#FFFF00', '#DC143C', '#FF1493', '#00FA9A'];

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
                        //html +='        <canvas id="chart'+i+'" height="150" width="150"></canvas>';
                        //html +='    <div id="chart'+i+'" class="chart"></div>';
                        html +='        <div class="sl-item">';
                        html +='                    <div class="sl-right">';
                        for (var j=0;j<response.pergunta[i].resposta.length;j++) {
                            html +='<div><a href="#">'+response.pergunta[i].resposta[j].titulo+'</a> <span class="pull-right label-info label">'+response.pergunta[i].resposta[j].qtd+'</span></div>'; 
                        }
                        html +='            </div>';
                        html +='        </div>';


                        
                        html +='<button id="view-grafico" rel="'+response.pergunta[i].id+'"  class="btn btn-success btn-rounded waves-effect waves-light m-t-20">Ver Gráfico</button>';
                        
                        html +='    </div>';
                        html +='</div>';
                    }
                }

                html +='<div id="responsive-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">';
                html +='    <div class="modal-dialog">';
                html +='        <div class="modal-content">';
                html +='            <div class="modal-header">';
                html +='                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';
                html +='                <h4 class="modal-title">Modal Content is Responsive</h4>'; 
                html +='            </div>';
                html +='            <div class="modal-body">';
                html +='                <canvas id="grafico" width="200" height="233" style="width: 100%; height: 100%;"></canvas>';
                html +='            </div>';
                html +='            <div class="modal-footer">';
                html +='                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>';
                html +='            </div>';
                html +='        </div>';
                html +='    </div>';
                html +='</div>';

                html +='</div>';
                $("#resultados").html(html);

            },
            error : onError
        });
    }

    list();

    $('button#view-grafico').livequery('click',function(event){
        //cont++;
        //$('button#view-grafico').on('shown.bs.modal', function (event) {

        //data-toggle="modal" data-target="#responsive-modal"
        $("#responsive-modal").modal("show");

        var params = {id: $(this).attr('rel')};
        //alert(params.id);
        params = JSON.stringify(params);
        app.util.getjson({
            url : "/controller/office/relatorio/list_grafico",
            method : 'POST',
            data: params,
            contentType : "application/json",
            success: function(response){
                
                var dt = new Array();
                var label = new Array();
                var cor = new Array();
                for (var j=0;j<response.length;j++) {
                    dt[j] = response[j].qtd;
                    label[j] = response[j].titulo;
                    cor[j] = cores[j];
                }
                
                var canvas = null;
                //myPieChart.destroy();

                var canvas= document.getElementById('grafico');
                var ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0,  canvas.width, canvas.height);
                
                //ctx = document.getElementById("grafico").getContext('2d');

                var data = null;
                data = {
                    datasets: [{
                        data: dt,
                        backgroundColor: cor
                    }],

                    // These labels appear in the legend and in the tooltips when hovering different arcs
                    labels: label
                };
                var myPieChart = null;
                myPieChart = new Chart(ctx,{
                    type: 'pie',
                    data: data,
                    options: {
                        legend: {
                            display: true,
                            labels: {
                                fontColor: 'rgb(255, 99, 132)'
                            }
                        }
                    }
                });

                alert(dt.length);

                dt = [];
                label = [];
                cor = [];
            
                //myPieChart.clear();

            },
            error : onError
        });
    });



    //alert("Chanou");

});