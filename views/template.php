<?php ?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/gif" href="<?php echo BASE_URL ?>assets/imagens/icon.png" sizes="32x32" />
    <meta property="ogg:title" content="<?= NAME_PROJECT ?>">
    <meta property="ogg:description" content="<?= NAME_PROJECT ?>">
    <title><?= NAME_PROJECT ?></title>
    <!-- Bootstrap -->
    <link href="<?php echo BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL ?>assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL ?>assets/css/jquery-ui.css">
    <link href="<?php echo BASE_URL ?>assets/css/select2.min.css" rel="stylesheet">

    <!-- jQuery (obrigatório para plugins JavaScript do Bootstrap) -->
    <script src="<?php echo BASE_URL ?>assets/js/jquery-3.1.1.min.js"></script>
    <script src="<?php echo BASE_URL ?>assets/js/select2.min.js"></script>
    <script src="<?php echo BASE_URL ?>assets/js/jquery-ui.js"></script>

    <script>
        var base_url = "<?php echo BASE_URL ?>";

        function mostrarConteudo() {
            var elemento = document.getElementById("tela_load");
            elemento.style.display = "none";

            var elemento = document.getElementById("tela_sistema");
            if (elemento) {
                elemento.style.display = "block";
            }

            var elemento = document.getElementById("interface_login");
            if (elemento) {
                elemento.style.display = "block";
            }
        }
    </script>
    <link rel="stylesheet" href="<?php echo BASE_URL ?>assets/css/estilo.css">
</head>

<body>
    <div id="tela_load">
        <img src="<?php echo BASE_URL ?>assets/imagens/loading.gif" style="display: block; margin: auto; margin-top: 300px;">
    </div>
    <div id="tela_sistema">
        <!-- menu -->
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?php echo BASE_URL ?>"><img src="<?php echo BASE_URL ?>assets/imagens/logo_menu.png" /></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="<?php echo BASE_URL ?>home"><i class="fas fa-home"></i> Início</a></li>
                        <?php if ($this->checkUser()) : ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-plus"></i> Cadastrar <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo BASE_URL ?>cadastrar/reserva"><i class="fas fa-plus"></i> Reserva</a></li>
                                    <?php if ($this->checkNivel() != false) : ?>
                                        <li><a href="<?php echo BASE_URL ?>cadastrar/horario"><i class="fas fa-plus"></i> Horário</a></li>
                                        <li><a href="<?php echo BASE_URL ?>cadastrar/equipamento"><i class="fas fa-plus"></i> Equipamento</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="<?php echo BASE_URL ?>cadastrar/usuario"><i class="fas fa-user-plus"></i> Usuário</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="<?php echo BASE_URL ?>cadastrar/administrador"><i class="fas fa-user-plus"></i> Administrador</a></li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-search"></i> Relatório <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo BASE_URL ?>relatorio/reserva"><i class="fas fa-search"></i> Reserva</a></li>
                                    <?php if ($this->checkNivel() != false) : ?>
                                        <li><a href="<?php echo BASE_URL ?>relatorio/dias_uteis"><i class="fas fa-search"></i> Dias úteis para reserva</a></li>
                                        <li><a href="<?php echo BASE_URL ?>relatorio/horario"><i class="fas fa-search"></i> Horário</a></li>
                                        <li><a href="<?php echo BASE_URL ?>relatorio/equipamento"><i class="fas fa-search"></i> Equipamento</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="<?php echo BASE_URL ?>relatorio/usuario"><i class="fas fa-users"></i> Usuário</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="<?php echo BASE_URL ?>relatorio/administrador"><i class="fas fa-user-friends"></i> Administrador</a></li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <li><a href="<?php echo BASE_URL ?>anexos"><i class="fas fa-paperclip"></i> Anexos</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <?php if ($this->checkUser() == false) : ?>
                            <li><a href="<?php echo BASE_URL ?>login"><i class="fas fa-user-lock"></i> Login</a></li>
                        <?php
                        endif;
                        if ($this->checkUser() == true) :
                        ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user-alt"></i> <?php echo $this->getNome() ?> <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <?php if ($this->checkNivel() != false) : ?>
                                        <li><a href="<?php echo BASE_URL ?>editar/administrador/<?php echo md5($this->getId()) ?>"><i class="fas fa-users-cog"></i> Editar Perfil</a></li>
                                    <?php else : ?>
                                        <li><a href="<?php echo BASE_URL ?>editar/usuario/<?php echo md5($this->getId()) ?>"><i class="fas fa-users-cog"></i> Editar Perfil</a></li>
                                    <?php endif ?>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="<?php echo BASE_URL ?>home/sair"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        <!--fim menu-->

        <?php $this->loadViewInTemplate($viewName, $viewData) ?>

    </div>
    <!-- /#tela_sistema -->

    <!-- Inclui todos os plugins compilados (abaixo), ou inclua arquivos separadados se necessário -->
    <script src="<?php echo BASE_URL ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo BASE_URL ?>assets/js/jquery.maskedinput.min.js"></script>
    <script src="<?php echo BASE_URL ?>assets/js/jquery.maskMoney.js"></script>
    <script src="<?php echo BASE_URL ?>assets/js/script.js"></script>
    <script src="<?php echo BASE_URL ?>assets/js/fontawesome-all.min.js"></script>
    <!--MODAL - ESTRUTURA BÁSICA-->
    <section class="modal fade" id="modal_recupera" tabindex="-1" role="dialog">
        <article class="modal-dialog modal-md" role="document">
            <section class="modal-content">
                <header class="modal-header bg-primary">
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <p class="panel-title">Mensagem</p>
                </header>
                <article class="modal-body">
                    <p class="text-justify">Lorem Ipsum Dolor!</p>
                </article>
                <footer class="modal-footer">
                    <button class="btn btn-default" type="button" data-dismiss="modal">Fechar</button>
                </footer>
            </section>
        </article>
    </section>
    <!--MODAL - ESTRUTURA BÁSICA-->
</body>

</html>