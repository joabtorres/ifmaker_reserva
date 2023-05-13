<div class="container-fluid" id="container-reserva">
    <div class="row">
        <div class="col-xs-12" id="pagina-header">
            <h3>Editar Reserva</h3>
        </div>
    </div> <!-- fim row-->
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="alert <?php echo (isset($erro['class'])) ? $erro['class'] : 'alert-warning'; ?> " role="alert" id="alert-msg">
                <button class="close" data-hide="alert">&times;</button>
                <div id="resposta"><?php echo (isset($erro['msg'])) ? $erro['msg'] : '<i class = "fa fa-info-circle" aria-hidden = "true"></i> Preencha os campos corretamente.'; ?></div>
            </div>
        </div>
        <div class="col-xs-12 clear">
            <form autocomplete="off" method="POST">
                <input type="hidden" name="nId" value="<?php echo !empty($reserva['id']) ? $reserva['id'] : 0; ?>" />
                <section class="panel panel-black">
                    <header class="panel-heading">
                        <h4 class="panel-title"><i class="fa fa-circle-notch pull-left"></i>Reserva</h4>
                    </header>
                    <article class="panel-body">
                        <div class="row">
                            <div class="form-group col-md-8 <?php echo (isset($reserva_erro['id_usuario']['class'])) ? $reserva_erro['id_usuario']['class'] : ''; ?>">
                                <label for='selectUser' class="control-label">Usuário: <?php echo (isset($reserva_erro['id_usuario']['msg'])) ? '<small><span class="glyphicon glyphicon-remove"></span> ' . $reserva_erro['id_usuario']['msg'] . ' </small>' : ''; ?></label>
                                <select class="form-control single-select" name="nUsuario" id="selectUser">
                                    <?php
                                    if (is_array($usuarios)) {
                                        foreach ($usuarios as $item) {
                                            if (!empty($reserva['id_usuario']) && $reserva['id_usuario'] == $item['id']) {
                                                echo "<option value='" . $item['id'] . "' selected>" . $item['categoria'] . ": " . $item['nome'] . " " . $item['sobrenome'];
                                                echo ($item['categoria'] != 'Aluno(a)') ? " - SIAPE: " . $item['matricula'] : " - Matricula: " . $item['matricula'];
                                                echo (!empty($item['curso'])) ? " - Curso: " . $item['curso'] : '';
                                                echo "</option>";
                                            } else {
                                                echo "<option value='" . $item['id'] . "'>" . $item['categoria'] . ": " . $item['nome'] . " " . $item['sobrenome'];
                                                echo ($item['categoria'] != 'Aluno(a)') ? " - SIAPE: " . $item['matricula'] : " - Matricula: " . $item['matricula'];
                                                echo (!empty($item['curso'])) ? " - Curso: " . $item['curso'] : '';
                                                echo "</option>";
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-4 <?php echo (isset($reserva_erro['id_equipamento']['class'])) ? $reserva_erro['id_equipamento']['class'] : ''; ?>">
                                <label for='iEquip' class="control-label">Equipamento: <?php echo (isset($reserva_erro['id_equipamento']['msg'])) ? '<small><span class="glyphicon glyphicon-remove"></span> ' . $reserva_erro['id_equipamento']['msg'] . ' </small>' : ''; ?></label>
                                <select class="form-control single-select" name="nEquipamento" id="iEquip">
                                    <?php
                                    if (is_array($equipamentos)) {
                                        foreach ($equipamentos as $item) {
                                            if (!empty($reserva['id_equipamento']) && $reserva['id_equipamento'] == $item['id']) {
                                                echo "<option value='" . $item['id'] . "' selected>" . $item['nome'] . "</option>";
                                            } else {
                                                echo "<option value='" . $item['id'] . "'>" . $item['nome'] . "</option>";
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-4 <?php echo (isset($reserva_erro['data_inicial']['class'])) ? $reserva_erro['data_inicial']['class'] : ''; ?>">
                                <label for="cDataInicial" class="control-label">Data Inicial: * <?php echo (isset($reserva_erro['data_inicial']['msg'])) ? '<small><span class="glyphicon glyphicon-remove"></span> ' . $reserva_erro['data_inicial']['msg'] . ' </small>' : ''; ?></label>
                                <input type="text" name="data_inicial" id="cDataInicial" class="form-control input-data <?php echo ($this->checkNivel()) ? 'input-calendario-administrador' : 'input-calendario-usuario'; ?>" placeholder="Exemplo: 00/00/0000" value='<?php echo isset($reserva['data_inicial']) ? $this->formatDateView($reserva['data_inicial']) : ""; ?>' />
                            </div>
                            <div class="form-group col-md-4 <?php echo (isset($reserva_erro['data_final']['class'])) ? $reserva_erro['data_final']['class'] : ''; ?>">
                                <label for="cDataFinal" class="control-label">Data Final: * <?php echo (isset($reserva_erro['data_final']['msg'])) ? '<small><span class="glyphicon glyphicon-remove"></span> ' . $reserva_erro['data_final']['msg'] . ' </small>' : ''; ?></label>
                                <input type="text" name="data_final" id="cDataFinal" class="form-control input-data <?php echo ($this->checkNivel()) ? 'input-calendario-administrador' : 'input-calendario-usuario'; ?>" placeholder="Exemplo: 00/00/0000" value='<?php echo isset($reserva['data_final']) ? $this->formatDateView($reserva['data_final']) : ""; ?>' />
                            </div>
                            <div class="form-group col-md-4 <?php echo (isset($reserva_erro['horario']['class'])) ? $reserva_erro['horario']['class'] : ''; ?>" id="form-horario">
                                <label for='iHorario' class="control-label">Horário: <?php echo (isset($reserva_erro['horario']['msg'])) ? '<small><span class="glyphicon glyphicon-remove"></span> ' . $reserva_erro['horario']['msg'] . ' </small>' : ''; ?> <span id="msgHorario"><i class="glyphicon glyphicon-remove"></i> Não há horário disponível</span></label>
                                <input type="hidden" id="iSetHorario" value="<?= !empty($reserva['horario_inicial']) && !empty($reserva['horario_final']) ? $reserva['horario_inicial'] . ' - ' . $reserva['horario_final'] : '' ?>">
                                <select class="form-control single-select" name="nHorario" id="iHorario">
                                </select>
                            </div>
                            <div id="iDiasdaSemana" class="hide">

                                <div class="col-md-12 <?php echo (isset($reserva_erro['dias']['class'])) ? $reserva_erro['dias']['class'] : ''; ?>">
                                    <label class="control-label"> Dia(s) da semana: <?php echo (isset($reserva_erro['dias']['msg'])) ? '<small><span class="glyphicon glyphicon-remove"></span> ' . $reserva_erro['dias']['msg'] . ' </small>' : ''; ?></label>
                                </div>
                                <div class="form-group col-md-2 <?php echo (isset($reserva_erro['dias']['class'])) ? $reserva_erro['dias']['class'] : ''; ?>">
                                    <div class="input-group">
                                        <span class="input-group-addon " id="iconNome"><input type="checkbox" name="nSegunda" id="iSegunda" value="1" <?php echo !empty($reserva['segunda']) ? 'checked="checked"' : '' ?> /></span>
                                        <label class="form-control" aria-describedby="iconNome" for="iSegunda">Segunda-feira</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-2 <?php echo (isset($reserva_erro['dias']['class'])) ? $reserva_erro['dias']['class'] : ''; ?>">
                                    <div class="input-group">
                                        <span class="input-group-addon " id="iconNome"><input type="checkbox" name="nTerca" id="iTerca" value="1" <?php echo !empty($reserva['terca']) ? 'checked="checked"' : '' ?> /></span>
                                        <label class="form-control" aria-describedby="iconNome" for="iTerca">Terça-feira</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-2 <?php echo (isset($reserva_erro['dias']['class'])) ? $reserva_erro['dias']['class'] : ''; ?>">
                                    <div class="input-group">
                                        <span class="input-group-addon" id="iconNome"><input type="checkbox" name="nQuarta" id="iQuarta" value="1" <?php echo !empty($reserva['quarta']) ? 'checked="checked"' : '' ?> /></span>
                                        <label class="form-control" aria-describedby="iconNome" for="iQuarta">Quarta-feira</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-2 <?php echo (isset($reserva_erro['dias']['class'])) ? $reserva_erro['dias']['class'] : ''; ?>">
                                    <div class="input-group">
                                        <span class="input-group-addon" id="iconNome"><input type="checkbox" name="nQuinta" id="iQuinta" value="1" <?php echo !empty($reserva['quinta']) ? 'checked="checked"' : '' ?> /></span>
                                        <label class="form-control" aria-describedby="iconNome" for="iQuinta">Quinta-feira</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-2 <?php echo (isset($reserva_erro['dias']['class'])) ? $reserva_erro['dias']['class'] : ''; ?>">
                                    <div class="input-group">
                                        <span class="input-group-addon" id="iconNome"><input type="checkbox" name="nSexta" id="iSexta" value="1" <?php echo !empty($reserva['sexta']) ? 'checked="checked"' : '' ?> /></span>
                                        <label class="form-control" aria-describedby="iconNome" for="iSexta">Sexta-feira</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-2 <?php echo (isset($reserva_erro['dias']['class'])) ? $reserva_erro['dias']['class'] : ''; ?>">
                                    <div class="input-group">
                                        <span class="input-group-addon" id="iconNome"><input type="checkbox" name="nSabado" id="iSAb" value="1" <?php echo !empty($reserva['sabado']) ? 'checked="checked"' : '' ?> /></span>
                                        <label class="form-control" aria-describedby="iconNome" for="iSAb">Sábado</label>
                                    </div>
                                </div>
                            </div>
                            <!-- fim<div id="iDiasdaSemana">-->

                            <div id="turmasDisc" class="hide">

                                <div class="form-group col-md-6 <?php echo (isset($reserva_erro['turma']['class'])) ? $reserva_erro['turma']['class'] : ''; ?>">
                                    <label for="iTurma" class="control-label">Turma: <?php echo (isset($reserva_erro['turma']['msg'])) ? '<small><span class="glyphicon glyphicon-remove"></span> ' . $reserva_erro['turma']['msg'] . ' </small>' : ''; ?></label>
                                    <input type="text" name="nTurma" id="iTurma" class="form-control" placeholder="Exemplo: TADS 15" value='<?php echo isset($reserva['turma']) ? $reserva['turma'] : ""; ?>' />
                                </div>
                                <div class="form-group col-md-6 <?php echo (isset($reserva_erro['disciplina']['class'])) ? $reserva_erro['disciplina']['class'] : ''; ?>">
                                    <label for="iDisciplina" class="control-label">Disciplina: <?php echo (isset($reserva_erro['disciplina']['msg'])) ? '<small><span class="glyphicon glyphicon-remove"></span> ' . $reserva_erro['disciplina']['msg'] . ' </small>' : ''; ?></label>
                                    <input type="text" name="nDisciplina" id="iDisciplina" class="form-control" placeholder="Exemplo: Banco de Dados" value='<?php echo isset($reserva['disciplina']) ? $reserva['disciplina'] : ""; ?>' />
                                </div>
                            </div>
                            <?php if ($this->checkNivel()) : ?>
                                <div class="form-group col-md-12">
                                    <span>Status:</span><br />
                                    <?php
                                    $status = array(array('nome' => 'Liberado', 'valor' => 1), array('nome' => 'Não Liberado', 'valor' => 0));
                                    foreach ($status as $statu) {
                                        if ($reserva['status'] == $statu['valor']) {
                                            echo ' <label ><input type="radio" name="nStatus" value="' . $statu['valor'] . '" checked /> ' . $statu['nome'] . '</label> ';
                                        } else {
                                            echo ' <label ><input type="radio" name="nStatus" value="' . $statu['valor'] . '" /> ' . $statu['nome'] . '</label> ';
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="form-group col-md-12 <?php echo (isset($reserva_erro['dias']['class'])) ? $reserva_erro['dias']['class'] : ''; ?>">
                                    <label class="control-label" id="iDescricao">Descrição: <?php echo (isset($reserva_erro['dias']['msg'])) ? '<small><span class="glyphicon glyphicon-remove"></span> ' . $reserva_erro['dias']['msg'] . ' </small>' : ''; ?></label>
                                    <textarea class="form-control" name="nDescricao" placeholder="Descrição"><?php echo !empty($reserva['descricao']) ? $reserva['descricao'] : '' ?></textarea>
                                </div>
                            <?php endif; ?>
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
        </div>
    </div> <!-- fim row-->
</div>