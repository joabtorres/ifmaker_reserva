<div class="container-fluid" id="section-relatorio_reserva">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12" id="pagina-header">
            <h3>Reserva</h3>

        </div>
    </div>
    <!--FIM pagina-header-->
    <div class="row">
        <div class="col-md-12 clear">
            <form method="GET" autocomplete="off" action="<?php echo BASE_URL ?>relatorio/reserva/1">
                <section class="panel panel-black">
                    <header class="panel-heading">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                            <h4 class="panel-title"><i class="fa fa-search pull-left"></i> Painel de busca <i class="fa fa-eye pull-right"></i></h4>
                        </a>
                    </header>
                    <div id="collapseOne" class="panel-collapse collapse">
                        <article class="panel-body">
                            <div class="row">
                                <div class="col-md-3 col-lg-2 form-group">
                                    <label for='iInicio'>Data de início: </label>
                                    <input type="text" id="iInicio" name="nInicio" class="form-control input-data date_serach" />
                                </div>
                                <div class="col-md-3 col-lg-2 form-group">
                                    <label for='iTermino'>Data de término: </label>
                                    <input type="text" id="iTermino" name="nTermino" class="form-control input-data date_serach" />
                                </div>

                                <div class="col-md-3 col-lg-2 form-group">
                                    <label for="iStatus" class="control-label">Status:</label><br />
                                    <select id="iStatus" name="nStatus" class="form-control single-select">
                                        <option value="" selected="selected">Todas</option>
                                        <option value="Liberado">Liberado</option>
                                        <option value="Inativo">Não Liberado</option>
                                    </select>
                                </div>

                                <div class="col-md-3 col-lg-2 form-group">
                                    <label for="iCategoria" class="control-label">Categoria:</label><br />
                                    <select id="iCategoria" name="nCategoria" class="form-control single-select">
                                        <option value="" selected="selected">Todas</option>
                                        <option value="Aluno(a)">Aluno(a)</option>
                                        <option value="Professor(a)">Professor(a)</option>
                                        <option value="Secretaria">Secretaria</option>
                                        <option value="Demanda Externa">Demanda Externa</option>
                                    </select>
                                </div>

                                <div class="col-md-12 col-lg-4 form-group">
                                    <label for="iDefesa" class="control-label">Usuário:</label><br />
                                    <select id="iDefesa" name="nUsuario" class="form-control single-select">
                                        <option value="" selected="selected">Todas</option>
                                        <?php
                                        if (is_array($usuarios)) {
                                            foreach ($usuarios as $item) {
                                                echo "<option value='" . $item['id'] . "'>" . $item['categoria'] . ": " . $item['nome'] . " " . $item['sobrenome'];
                                                echo ($item['categoria'] != 'Aluno(a)') ? " - SIAPE: " . $item['matricula'] : " - Matricula: " . $item['matricula'];
                                                echo (!empty($item['curso'])) ? " - Curso: " . $item['curso'] : '';
                                                echo "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <button type="submit" name="nBuscarBT" value="BuscarBT" class="btn btn-warning"><i class="fa fa-search pull-left"></i> Buscar</button>
                                </div>
                            </div>
                        </article>
                    </div>
                </section>
            </form>
        </div>
    </div>
    <div class="row">
        <?php
        if (!empty($reservas)) {
        ?>
            <div class="col-md-12">
                <section class="panel panel-black">
                    <header class="panel-heading">
                        <h4 class="text-upercase"> Resultados encontrados</h4>
                    </header>
                    <article class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-condensed">
                            <tr>
                                <th>#</th>
                                <th>Laboratório</th>
                                <th>Usuário</th>
                                <th>Categoria</th>
                                <th>Data</th>
                                <th>Horário</th>
                                <th>Status</th>
                                <th class="table-acao" width="120px">Ação</th>
                            </tr>
                            <?php
                            $qtd = 1;
                            foreach ($reservas as $indice) :
                            ?>
                                <tr>
                                    <td><?php echo $qtd ?></td>
                                    <td><?php echo !empty($indice['lab_nome']) ? $indice['lab_nome'] : '' ?></td>
                                    <td><?php echo !empty($indice['nome']) ? $indice['nome'] . ' ' . $indice['sobrenome'] : '' ?></td>
                                    <td><?php echo !empty($indice['categoria']) ? $indice['categoria'] : '' ?></td>
                                    <td><?php echo !empty($indice['data_inicial']) ? $this->formatDateView($indice['data_inicial']) : '' ?> - <?php echo !empty($indice['data_final']) ? $this->formatDateView($indice['data_final']) : '' ?></td>
                                    <td><?php echo !empty($indice['horario_inicial']) ? $this->formatTimeView($indice['horario_inicial']) . ' - ' . $this->formatTimeView($indice['horario_final']) : '' ?></td>
                                    <td><?php echo !empty($indice['status']) ? '<span class="bg-success padding-5">Liberado</span>' : '<span class="padding-5 bg-danger">Não Liberado</span>' ?></td>
                                    <td class="table-acao text-center">
                                        <?php if ($this->checkNivel() || $indice['status'] != 1) : ?>
                                            <a class="btn btn-primary btn-xs" href="<?php echo BASE_URL . 'editar/reserva/' . md5($indice['id']); ?>" title="Editar"><i class="fa fa-pencil-alt"></i></a>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal_view_<?php echo md5($indice['id']) ?>" title="visualizar"><i class="fas fa-eye"></i></button>
                                        <?php if ($this->checkNivel() || $indice['status'] != 1) : ?>
                                            <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal_relatorio_<?php echo md5($indice['id']) ?>" title="Excluir"><i class="fa fa-trash"></i></button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php
                                $qtd++;
                            endforeach;
                            ?>
                        </table>
                    </article>
                </section>
            </div>
        <?php
        } else {
            echo '<div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <i class="fa fa-times"></i> Desculpe, não foi possível localizar nenhum registro !
                    </div>
                </div>';
        }
        ?>
    </div>
    <!--inicio da paginação-->
    <?php
    if (isset($paginas) && ceil($paginas) > 1) {
    ?>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <ul class="pagination">
                    <?php
                    echo "<li><a href='" . BASE_URL . "relatorio/reserva/1" . $metodo_buscar . "'>&laquo;</a></li>";
                    for ($p = 0; $p < ceil($paginas); $p++) {
                        if ($pagina_atual == ($p + 1)) {
                            echo "<li class='active'><a href='" . BASE_URL . "relatorio/reserva/" . ($p + 1) . $metodo_buscar . "'>" . ($p + 1) . "</a></li>";
                        } else {
                            echo "<li><a href='" . BASE_URL . "relatorio/reserva/" . ($p + 1) . $metodo_buscar . "'>" . ($p + 1) . "</a></li>";
                        }
                    }
                    echo "<li><a href='" . BASE_URL . "relatorio/reserva/" . ceil($paginas) . $metodo_buscar . "'>&raquo;</a></li>";
                    ?>
                </ul>
            </div>
        </div>

    <?php }
    ?>
    <!--fim da paginação-->
</div>

<?php
if (isset($reservas) && is_array($reservas)) :
    foreach ($reservas as $indice) :
?>
        <!--MODAL - ESTRUTURA BÁSICA-->
        <section class="modal fade" id="modal_relatorio_<?php echo md5($indice['id']) ?>" tabindex="-1" role="dialog">
            <article class="modal-dialog modal-md" role="document">
                <section class="modal-content">
                    <header class="modal-header bg-danger">
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4>Deseja remover este registro?</h4>
                    </header>
                    <article class="modal-body">
                        <ul class="list-unstyled">
                            <li><b>Laboratório: </b><?php echo !empty($indice['lab_nome']) ? $indice['lab_nome'] : '' ?></li>
                            <li><b>Usuário: </b><?php echo !empty($indice['nome']) ? $indice['categoria'] . ': ' . $indice['nome'] . ' ' . $indice['sobrenome'] : '' ?></li>
                            <li><b>Data: </b><?php echo !empty($indice['data_inicial']) ? $this->formatDateView($indice['data_inicial']) : '' ?> - <?php echo !empty($indice['data_final']) ? $this->formatDateView($indice['data_final']) : '' ?></li>
                            <li><b>Horário: </b><?php echo !empty($indice['horario_inicial']) ? $this->formatTimeView($indice['horario_inicial']) . ' - ' . $this->formatTimeView($indice['horario_final']) : '' ?></li>
                            <li><b>Status: </b><?php echo !empty($indice['status']) ? '<span class="bg-success ">Liberado</span>' : '<span class="bg-danger">Não Liberado</span>' ?></li>

                        </ul>
                        <p class="text-justify text-danger"><span class="font-bold">OBS : </span> Ao clicar em "Excluir", este registro e todos registos relacionados ao mesmo deixaram de existir no sistema.</p>
                    </article>
                    <footer class="modal-footer">
                        <a class="btn btn-danger pull-left" href="<?php echo BASE_URL . 'excluir/reserva/' . md5($indice['id']) ?>"> <i class="fa fa-trash"></i> Excluir</a>
                        <button class="btn btn-default" type="button" data-dismiss="modal"><i class="fa fa-times"></i> Fechar</button>
                    </footer>
                </section>
            </article>
        </section>

        <section class="modal fade" id="modal_view_<?php echo md5($indice['id']) ?>" tabindex="-1" role="dialog">
            <article class="modal-dialog modal-md" role="document">
                <section class="modal-content">
                    <header class="modal-header bg-success">
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4> Sirin - Reserva COD<?php echo str_pad($indice['id'], 5, '0', STR_PAD_LEFT) ?></h4>
                    </header>
                    <article class="modal-body">
                        <ul class="list-unstyled">
                            <li><b>Laboratório: </b><?php echo !empty($indice['lab_nome']) ? $indice['lab_nome'] : '' ?></li>
                            <li><?php echo !empty($indice['nome']) ? '<b> ' . $indice['categoria'] . ':</b> ' . $indice['nome'] . ' ' . $indice['sobrenome'] : '' ?></li>
                            <li><b>Data: </b><?php echo !empty($indice['data_inicial']) ? $this->formatDateView($indice['data_inicial']) : '' ?> - <?php echo !empty($indice['data_final']) ? $this->formatDateView($indice['data_final']) : '' ?></li>
                            <li><b>Horário: </b><?php echo !empty($indice['horario_inicial']) ? $this->formatTimeView($indice['horario_inicial']) . ' - ' . $this->formatTimeView($indice['horario_final']) : '' ?></li>
                            <li><b>Status: </b><?php echo !empty($indice['status']) ? '<span class="bg-success ">Liberado</span>' : '<span class="bg-danger">Não Liberado</span>' ?></li>
                            <?php echo !empty($indice['turma']) ? '<li><b>Turma: </b>' . $indice['turma'] . '</li>' : ''; ?>
                            <?php echo !empty($indice['disciplina']) ? '<li><b>Disciplina: </b>' . $indice['disciplina'] . '</li>' : ''; ?>
                            <li><b>Dia(s) da semana:</b>
                                <?php echo !empty($indice['segunda']) ? '  Segunda-Feira; ' : ''; ?>
                                <?php echo !empty($indice['terca']) ? '  Terça-Feira; ' : ''; ?>
                                <?php echo !empty($indice['quarta']) ? '  Quarta-Feira; ' : ''; ?>
                                <?php echo !empty($indice['quinta']) ? '  Quinta-Feira; ' : ''; ?>
                                <?php echo !empty($indice['sexta']) ? '  Sexta-Feira; ' : ''; ?>
                                <?php echo !empty($indice['sabado']) ? '  Sábado; ' : ''; ?>
                            </li>
                            <?php if (!empty($indice['descricao'])) : ?>
                                <li><b>Descrição:</b>
                                    <p class="text-justify"><?php echo $indice['descricao'] ?></p>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </article>
                    <footer class="modal-footer">
                        <button class="btn btn-default" type="button" data-dismiss="modal"><i class="fa fa-times"></i> Fechar</button>
                    </footer>
                </section>
            </article>
        </section>
<?php
    endforeach;
endif;
?>