//variable global
var questionarios = {};

$(document).ready(function(){

    function get(){
        var params = {hash: $('#hash').val()};
        params = JSON.stringify(params);

        //list
        app.util.getjson({
            url : "/controller/guest/questionario/get",
            method : 'POST',
            contentType : "application/json",
            data: params,
            success: function(response){
                if(response.id){
                    $('#loading').addClass('hidden');
                    $('#title').removeClass('hidden');
                    $('#items').removeClass('hidden');
                    $('#send').removeClass('hidden');

                    //title
                    $('#title h3').html(response.titulo);
                    $('#title p').html(response.introducao);

                    //items
                    var html = '';
                    for (var i=0;i<response.pergunta.length;i++) {
                        var pergunta = response.pergunta[i];
                        switch(parseInt(pergunta.tipo)){
                            case 1:
                                html += '<div class="col-md-12">'+
                                            '<div class="form-group">'+
                                                '<h5>'+pergunta.titulo+'</h5>'+
                                                '<input type="text" class="form-control input-lg" id="resposta'+pergunta.id+'" name="resposta'+pergunta.id+'" readonly>'+
                                            '</div>'+
                                        '</div>';
                            break;
                            case 2:
                                html += '<div class="col-md-12">'+
                                            '<div class="form-group">'+
                                            '<h5>'+pergunta.titulo+'</h5>';
                                for (var x=0;x<pergunta.resposta.length;x++) {
                                    var resposta = pergunta.resposta[x];
                                    html += '<div class="radio-styled radio-danger">'+
                                                '<label>'+
                                                    '<input id="resposta'+pergunta.id+'" name="resposta'+pergunta.id+'" type="radio" value="'+resposta.id+'" disabled>'+
                                                    '<span>'+resposta.titulo+'</span>'+
                                                '</label>'+
                                            '</div>';
                                }
                                html +=     '</div>'+
                                        '</div>';
                            break;
                            case 3:
                                html += '<div class="col-md-12">'+
                                            '<div class="form-group">'+
                                            '<h5>'+pergunta.titulo+'</h5>';
                                for (var x=0;x<pergunta.resposta.length;x++) {
                                    var resposta = pergunta.resposta[x];
                                    html += '<div class="checkbox-styled checkbox-danger">'+
                                                '<label>'+
                                                    '<input id="resposta'+pergunta.id+'" name="resposta'+pergunta.id+'[]" type="checkbox" value="'+resposta.id+'" disabled>'+
                                                    '<span>'+resposta.titulo+'</span>'+
                                                '</label>'+
                                            '</div>';
                                }
                                html +=     '</div>'+
                                        '</div>';
                            break;
                        }
                    }

                    $('#items').html(html);

                }else{
                    window.location.href = "/404";
                }
            },
            error : onError
        });
    }

	function onError(response) {
      console.log(response);
    }

    //init
    get();

});