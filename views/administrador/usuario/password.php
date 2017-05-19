<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title" id="exampleModalLabel1">Mudar senha</h4> 
</div>
<div class="modal-body">

    <div id="errorModal" class="hidden">
        <div class="alert alert-warning">
            <p></p>
        </div>
    </div>

    <div id="successModal" class="hidden">
        <div class="alert alert-success">
            <p></p>
        </div>
    </div>

    <form id="formMudarsenha">
        <div class="form-group">
            <label for="senha" class="control-label">Nova senha:</label>
            <input type="password" class="form-control" id="senha" name="senha"> 
        </div>
        <div class="form-group">
            <label for="confirmasenha" class="control-label">Confirmar senha:</label>
            <input type="password" class="form-control" id="confirmasenha" name="confirmasenha"> 
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