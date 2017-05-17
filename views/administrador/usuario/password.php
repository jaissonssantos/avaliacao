<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title" id="exampleModalLabel1">Mudar senha</h4> 
</div>
<div class="modal-body">
    <form id="formMudarsenha">
        <div class="form-group">
            <label for="senha" class="control-label">Senha:</label>
            <input type="text" class="form-control" id="senha"> 
        </div>
        <div class="form-group">
            <label for="confirmarsenha" class="control-label">Confirmar senha:</label>
            <input type="text" class="form-control" id="confirmarsenha"> 
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
    <button type="button" id="salvarMudarsenha" class="btn btn-success">Salvar</button>
</div>

<!-- javascripts -->
<script type="text/javascript" src="assets/javascript/jquery.validate.min.js"></script>
<script type="text/javascript" src="javascripts/administrador/usuario/password.js"></script>