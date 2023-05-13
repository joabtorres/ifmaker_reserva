<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12" id="pagina-header">
            <h3>Cadastrar Administrador</h3>
        </div>
    </div>
    <!--FIM pagina-header-->
    <article class="clear" id="container-usuario-form">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="alert <?php echo (isset($erro['class'])) ? $erro['class'] : 'alert-warning'; ?> " role="alert" id="alert-msg">
                    <button class="close" data-hide="alert">&times;</button>
                    <div id="resposta"><?php echo (isset($erro['msg'])) ? $erro['msg'] : ' <i class="fa fa-info-circle" aria-hidden="true"></i> Não é possível cadastrar um Administrador com e-email e matricula/Siape já cadastrado.'; ?></div>
                </div>
            </div>
        </div>
        <form method="POST" enctype="multipart/form-data" autocomplete="off">
            <section class="panel panel-black">
                <header class="panel-heading">
                    <p class="panel-title"><i class="fa fa-user-plus"></i> Administrador</p>
                </header>
                <article class="panel-body">
                    <div class="col-md-6">
                        <div class="form-group <?php echo (isset($usuario_erro['nome']['class'])) ? $usuario_erro['nome']['class'] : ''; ?>">
                            <label for="cNome" class="control-label">Nome: * <?php echo (isset($usuario_erro['nome']['msg'])) ? '<small><span class="glyphicon glyphicon-remove"></span> ' . $usuario_erro['nome']['msg'] . ' </small>' : ''; ?></label>
                            <input type="text" name="nNome" id="cNome" class="form-control" placeholder="Exemplo: Rodrigo" value='<?php echo isset($usuario['nome']) ? $usuario['nome'] : ""; ?>' />
                        </div>
                        <div class="form-group <?php echo (isset($usuario_erro['sobrenome']['class'])) ? $usuario_erro['sobrenome']['class'] : ''; ?>">
                            <label for="cSobrenome" class="control-label">Sobrenome:* <?php echo (isset($usuario_erro['sobrenome']['msg'])) ? '<small><span class="glyphicon glyphicon-remove"></span> ' . $usuario_erro['sobrenome']['msg'] . ' </small>' : ''; ?></label>
                            <input type="text" name="nSobrenome" id="cSobrenome" class="form-control" placeholder="Exemplo: Silva Braga" value='<?php echo isset($usuario['sobrenome']) ? $usuario['sobrenome'] : ""; ?>' />
                        </div>
                        <div class="form-group <?php echo (isset($usuario_erro['matricula']['class'])) ? $usuario_erro['matricula']['class'] : ''; ?>">
                            <label for="cMatricula" class="control-label">Matricula/Siape:* <?php echo (isset($usuario_erro['matricula']['msg'])) ? '<small><span class="glyphicon glyphicon-remove"></span> ' . $usuario_erro['matricula']['msg'] . ' </small>' : ''; ?></label>
                            <input type="text" name="nMatricula" id="cMatricula" class="form-control" placeholder="Exemplo: 2015445544" value='<?php echo isset($usuario['matricula']) ? $usuario['matricula'] : ""; ?>' />
                        </div>
                        <div class="form-group <?php echo (isset($usuario_erro['email']['class'])) ? $usuario_erro['email']['class'] : ''; ?>">
                            <label for="cEmail" class="control-label">E-mail:* <?php echo (isset($usuario_erro['email']['msg'])) ? '<small><span class="glyphicon glyphicon-remove"></span> ' . $usuario_erro['email']['msg'] . ' </small>' : ''; ?></label>
                            <input type="email" name="nEmail" id="cEmail" class="form-control" placeholder="Exemplo: rr.braga@gmail.com" value='<?php echo isset($usuario['email']) ? $usuario['email'] : ""; ?>' />
                        </div>
                        <div class="form-group <?php echo (isset($usuario_erro['senha']['class'])) ? $usuario_erro['senha']['class'] : ''; ?>">
                            <label for="cSenha" class="control-label">Senha:* <?php echo (isset($usuario_erro['senha']['msg'])) ? '<small><span class="glyphicon glyphicon-remove"></span> ' . $usuario_erro['senha']['msg'] . ' </small>' : ''; ?></label>
                            <input type="password" name="nSenha" id="cSenha" class="form-control" />
                        </div>
                        <div class="form-group <?php echo (isset($usuario_erro['senha']['class'])) ? $usuario_erro['senha']['class'] : ''; ?>">
                            <label for="cRepetirSenha" class="control-label">Repetir Senha:* <?php echo (isset($usuario_erro['senha']['msg'])) ? '<small><span class="glyphicon glyphicon-remove"></span> ' . $usuario_erro['senha']['msg'] . ' </small>' : ''; ?></label>
                            <input type="password" name="nRepetirSenha" id="cRepetirSenha" class="form-control" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for='iCargo'>Comitê Gestor:* </label>
                            <select class="form-control single-select" name="nCoordenacao" id="iCargo">
                                <?php
                                $array = array('Secretaria');
                                for ($i = 0; $i < count($array); $i++) {
                                    if (!empty($usuario['cargo']) && $usuario['cargo'] == $array[$i]) {
                                        echo "<option value='" . $array[$i] . "' selected>" . $array[$i] . "</option>";
                                    } else {
                                        echo "<option value='" . $array[$i] . "'>" . $array[$i] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <span>Sexo:</span><br />

                            <?php
                            if (!empty($usuario['sexo'])) {

                                $sexos = array(array('genero' => 'Masculino', 'sigla' => 'M'), array('genero' => 'Feminino', 'sigla' => 'F'));
                                foreach ($sexos as $sexo) {
                                    if ($usuario['sexo'] == $sexo['sigla']) {
                                        echo ' <label><input type="radio" name="nSexo" value="' . $sexo["sigla"] . '" checked onclick="readDefaultURL()"/> ' . $sexo["genero"] . ' </label>';
                                    } else {
                                        echo ' <label><input type="radio" name="nSexo" value="' . $sexo["sigla"] . '" onclick="readDefaultURL()"/> ' . $sexo["genero"] . ' </label>';
                                    }
                                }
                            } else {
                                echo '<label><input type="radio" name="nSexo" value="M" checked onclick="readDefaultURL()"/> Masculino</label> ';
                                echo ' <label><input type="radio" name="nSexo" value="F" onclick="readDefaultURL()"/> Feminino</label>';
                            }
                            ?>
                        </div>
                        <div class="form-group">
                            <span>Status da conta:</span><br />
                            <?php
                            if (isset($usuario['status'])) {
                                $status = array(array('nome' => 'Ativo', 'valor' => '1'), array('nome' => 'Inativo', 'valor' => '0'));
                                foreach ($status as $statu) {
                                    if ($usuario['status'] == $statu['valor']) {
                                        echo ' <label><input type="radio" name="nStatus" value="' . $statu['valor'] . '" checked /> ' . $statu['nome'] . '</label> ';
                                    } else {
                                        echo ' <label><input type="radio" name="nStatus" value="' . $statu['valor'] . '" /> ' . $statu['nome'] . '</label> ';
                                    }
                                }
                            } else {
                                echo ' <label><input type="radio" name="nStatus" value="1" checked/> Ativo</label> ';
                                echo ' <label><input type="radio" name="nStatus" value="0"/> Inativo </label> ';
                            }
                            ?>
                        </div>

                        <p class="text-center" style="margin-top: 10%;" id="fotos">
                            <img src="<?php echo BASE_URL ?>assets/imagens/user_masculino.png" class="img-center" alt="Usuario" id="viewImagem-1" />
                            <input type="hidden" name="qtd_fotos" value="1">
                            <label class="btn btn-warning" onclick="readDefaultURL()">Padrão</label>
                            <label class="btn btn-success" for="cFileImagem">Escolher Imagem</label>
                            <input type="file" name="tImagem-1" id="cFileImagem" onchange="readURL(this)" />
                        </p>

                    </div>
                </article>
            </section>
            <div class="row">
                <div class="form-group col-xs-12">
                    <button type="submit" class="btn btn-success" name="nSalvar" value="Salvar"><i class="fa fa-check-circle" aria-hidden="true"></i> Salvar</button>
                    <a href="<?php echo BASE_URL ?>home" class="btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i> Cancelar</a>
                </div>
            </div>
        </form>
    </article><!--FIM CONTAINER-USUARIO-->
    <!--FIM .ROW-->
</div>
<!-- /#section-container -->