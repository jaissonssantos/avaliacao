//variable global
var usuarios = {};

$(document).ready(function(){

    //select
    $('.selectpicker').selectpicker();

    //check-switch
    var check = $('div#pergunta input[type="checkbox"][class="js-switch"][data-id="1"]');
    var s = new Switchery(check[0], check.data());
    // $('.js-switch').each(function () {
        // new Switchery($(this)[0], $(this).data());
    // });

    //validate
    $('form#formQuestionario').validate({
        rules: {
            nome: {
                required: true,
                minlength: 5
            },
            email: {
                required: true, 
                email: true
            },
            perfil: { 
                required: true
            },
            senha: {
                required: true,
                minlength: 5
            },
            confirmasenha: {
                required: true,
                equalTo: "#senha"
            }
        },
        messages: {
            nome: { 
                required: 'Preencha o nome de usuário',
                minlength: 'Vamos lá o nome de usuário deve ter cinco caracteres'
            },
            email: { 
                required: 'Preencha seu email', 
                email: 'Ops, tem certeza que é um email válido?'
            },
            perfil: { 
                required: 'Escolha o nível de acesso do usuário'
            },
            senha: { 
                required: 'Preencha o campo senha',
                minlength: 'Para a segurança do usuário a senha deve ter no mínimo cinco caracteres'
            },
            confirmasenha: { 
                required: 'Vamos lá, confirme a senha',
                equalTo: 'Pelo que estou vendo as senhas não coincidem, tente novamente'
            }
        },
        highlight: function (element, errorClass, validClass) {
            if (element.type === "radio") {
                this.findByName(element.name).addClass(errorClass).removeClass(validClass);
                $(element).closest('.form-group').removeClass('has-success has-feedback').addClass('has-error has-feedback');
            } else {
                $(element).closest('.form-group').removeClass('has-success has-feedback').addClass('has-error has-feedback');
                $(element).closest('.form-group').find('i.fa').remove();
                $(element).closest('.form-group').append('<i class="fa fa-times fa-validate form-control-feedback fa-absolute"></i>');
            }
        },
        unhighlight: function (element, errorClass, validClass) {
            if (element.type === "radio") {
                this.findByName(element.name).removeClass(errorClass).addClass(validClass);
            } else {
                $(element).closest('.form-group').removeClass('has-error has-feedback').addClass('has-success has-feedback');
                $(element).closest('.form-group').find('i.fa').remove();
                $(element).closest('.form-group').append('<i class="fa fa-check fa-validate form-control-feedback fa-absolute"></i>');
            }
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else if (element.attr("type") == "radio") {
                error.insertAfter(element.parent().parent());
            }else{
                error.insertAfter(element);
            }
        }
    });

    //add
    $('button#add').livequery('click',function(event){
        var pergunta = $('div#pergunta:first').clone();
        $('#perguntas').append(pergunta)

        var count = $('div#pergunta').length;
        console.log('qtd: ' + count);

        var item = $('div#pergunta:last')
        // var seletepicker = item.find('div.bootstrap-select');

        item.attr('data-id',count);
        item.find('input.js-switch').attr('data-id',count);
        item.find('div#tipos').html('');

        var tipo = '<select id="selectpicker" class="selectpicker'+count+'" data-style="form-control">'+
                        '<option data-icon="ti-align-left"> Resposta curta</option>'+
                        '<option data-icon="fa fa-check-circle"> Múltipla escolha</option>'+
                        '<option data-icon="fa fa-check-square"> Caixas de seleção</option>'+
                    '</select>';
        item.find('div#tipos').html(tipo);
        item.find('div#tipos select.selectpicker'+count).selectpicker();
        item.find('div#tipos select.selectpicker'+count).selectpicker('refresh');

        // item.find('select.selectpicker').selectpicker('destroy');
        // item.find('select.selectpicker').selectpicker();
        // item.find('select.selectpicker').selectpicker('refresh');
        // item.find('select.selectpicker').selectpicker('render');

        // item.find('select.selectpicker').selectpicker('refresh');
        // sl = seletepicker.find('.btn-group:last').html();
        // seletepicker.find('.btn-group:first').html(sl);
        // console.log(sl);
        // seletepicker.find('button:first').remove();
        // seletepicker.find('div.dropdown-menu:first').remove();

        item.find('button#pergunta-excluir').removeClass('hidden');
        item.find('button#pergunta-duplicar').removeClass('hidden');
        var check = item.find('input.js-switch');
        var switchery = new Switchery(check[0],check.data());

        //delete 
        check.parent().find('span.switchery:last').remove();

        return false;
    });

    // $('#selectpicker').on('changed.bs.select', function (e, clickedIndex, newValue, oldValue) {
    //     var selected = $(e.currentTarget).val();
    //     console.log(selected);
    // });

    //selectpicker change
    $('select#selectpicker').on('change', function(){
        var selected = $('#selectpicker option:selected').val();
        console.log(selected);
    });

    // $('.myDropDown.bootstrap-select').on('changed.bs.select', function(){

    // });
    // $(".selectpicker option").on("click", function(){
    //     console.log('passou aqui!');
    //     var selected = $(this).val();
    //     console.log(selected);
    // });

    //duplicate
    $('button#pergunta-excluir').livequery('click',function(event){
        $(this).parents('#pergunta').remove();
    });

    //save
    $('button#salvar').livequery('click',function(event){
        if($("form#formUsuario").valid()){
            usuarios = {
                nome: $('#nome').val(),
                sobrenome: $('#sobrenome').val(),
                email: $('#email').val(),
                perfil: $('#perfil').val(),
                senha: $('#senha').val()
            };

            //params
            var params = {};
            params = JSON.stringify(usuarios);

            $('button#salvar').html('Processando...');
            $('button#salvar').prop("disabled",true);
            $('button#cancelar').prop("disabled",true);

            app.util.getjson({
                url : "/controller/office/usuario/create",
                method : 'POST',
                contentType : "application/json",
                data: params,
                success: function(response){
                    if(response.success){
                        setSession('success', response.success);
                        window.location.href = "/office/usuario";
                    }
                },
                error : function(response){
                    response = JSON.parse(response.responseText);
                    $('#error').removeClass('hidden');
                    $('#error').find('.alert p').html(response.error);
                    $('button#salvar').html('Salvar');
                    $('button#salvar').prop("disabled",false);
                    $('button#cancelar').prop("disabled",false);
                }
            });
        }else{
            $("form#formUsuario").valid();
        }
        return false;
    });

    //cancel
    $('button#cancelar').livequery('click',function(event){
        window.location.href = "/office/questionario/";
        return false;
    });

	function onError(response) {
      console.log(response);
    }

});