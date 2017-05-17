<!-- Left navbar-header -->
<div class="navbar-default sidebar" role="navigation">

    <div class="sidebar-nav navbar-collapse slimscrollsidebar">

        <ul class="nav" id="side-menu">
            <li class="user-pro">
                <a href="#" class="waves-effect">
                    <img src="assets/images/users.png" 
                        alt="user-img" 
                        class="img-circle"> 
                        <span class="hide-menu">
                            <?=$_SESSION['avaliacao_nome'].' '.$_SESSION['avaliacao_sobrenome']?>
                        <span class="fa arrow"></span>
                    </span>
                </a>
                <ul class="nav nav-second-level">
                    <li><a id="minhaconta" href="javascript:void(0)"><i class="ti-user"></i> Minha conta</a></li>
                    <li><a id="mudarsenha" href="javascript:void(0)"><i class="ti-key"></i> Mudar senha</a></li>
                    <li><a id="sair" href="javascript:void(0)"><i class="fa fa-power-off"></i> Sair</a></li>
                </ul>
            </li>
            <li class="nav-small-cap m-t-10">Funções</li>

            <li> <a href="<?=($_SESSION['avaliacao_gestor'] == 1 ? '/administrador/dashboard' : '/office/dashboard')?>" class="waves-effect"><i class="linea-icon linea-basic fa-fw" data-icon="v"></i> <span class="hide-menu"> Dashboard </span></a></li>

            <?php if(isset($_SESSION['avaliacao_gestor']) && $_SESSION['avaliacao_gestor'] == 1){ ?>
                <li> <a href="<?=($_SESSION['avaliacao_gestor'] == 1 ? '/administrador/estabelecimento' : '/office/dashboard')?>" class="
                waves-effect"><i class="icon-directions fa-fw" data-icon="v"></i> <span class="hide-menu"> Estabelecimentos </span></a>
                </li>
            <?php } ?>

            <?php if(isset($_SESSION['avaliacao_gestor']) && $_SESSION['avaliacao_gestor'] != 1){ ?>
                
                <li> 
                    <a href="/office/cliente" class="waves-effect">
                        <i class="icon-people p-r-10" data-icon="v"></i> <span class="hide-menu"> Clientes </span>
                    </a>
                </li>

                <li> 
                    <a href="/office/questionario" class="waves-effect">
                        <i class="icon-chart p-r-10" data-icon="v"></i> <span class="hide-menu"> Questionários </span>
                    </a>
                </li>

                <li> 
                    <a href="/office/usuario" class="waves-effect"><i class="icon-user p-r-10" data-icon="v"></i> <span class="hide-menu"> Usuários </span>
                    </a>
                </li>
            <?php } ?>

        </ul>
    </div>
</div>
<!-- Left navbar-header end -->