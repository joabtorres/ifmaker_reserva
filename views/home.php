<div class="container-fluid" id="homePage2">
    <div class="row">
        <div class="col-xs-12" id="pagina-header">
            <h3>Início</h3>
        </div>
        <div class="col-xs-12">

            <div class="row">
                <div class="col-xs-12">
                    <ul class="list-inline text-center">
                        <li class="bg-success list_prioridade"><i class="fas fa-users"></i> Professor(a)</li>
                        <li class="bg-info list_prioridade"><i class="fas fa-users"></i> Aluno(a)</li>
                        <li class="bg-danger list_prioridade"><i class="fas fa-users"></i> Secretaria</li>
                        <li class="bg-warning list_prioridade"><i class="fas fa-users"></i> Demanda Externa</li>
                    </ul>
                </div>
                <div class="col-md-3 hidden-sm hidden-xs">
                    <p class="text-left"><a href="<?php echo BASE_URL ?>home/index/<?php echo (($mes - 1) == 0) ? 12 . '/' . ($ano - 1) : ($mes - 1) . '/' . $ano ?>" class="btn btn-success"><i class="fas fa-angle-double-left"></i> Mês Anterior </a> </p>
                </div>
                <div class="col-md-6 hidden-sm hidden-xs">
                    <p class="text-center"><a href="<?php echo BASE_URL ?>home/index/<?php echo date('m') . '/' . date('Y') ?>" class="btn btn-primary"><i class="fas fa-angle-double-right"></i> Mês Atual <i class="fas fa-angle-double-left"></i></a></p>
                </div>
                <div class="col-md-3 hidden-sm hidden-xs">
                    <p class="text-right"><a href="<?php echo BASE_URL ?>home/index/<?php echo (($mes + 1) > 12) ? 1 . '/' . ($ano + 1) : ($mes + 1) . '/' . $ano ?>" class="btn btn-success"> Próximo Mês <i class="fas fa-angle-double-right"></i></a> </p>
                </div>

                <div class="col-sm-12 visible-sm visible-xs text-center">
                    <a href="<?php echo BASE_URL ?>home/index/<?php echo (($mes - 1) == 0) ? 12 . '/' . ($ano - 1) : ($mes - 1) . '/' . $ano ?>" class="btn btn-success  "><i class="fas fa-angle-double-left"></i> Mês Anterior</a>
                    <a href="<?php echo BASE_URL ?>home/index/<?php echo date('m') . '/' . date('Y') ?>" class="btn btn-primary"><i class="fas fa-angle-double-right"></i> Mês Atual <i class="fas fa-angle-double-left"></i></a>
                    <a href="<?php echo BASE_URL ?>home/index/<?php echo (($mes + 1) > 12) ? 1 . '/' . ($ano + 1) : ($mes + 1) . '/' . $ano ?>" class="btn btn-success "> Próximo Mês <i class="fas fa-angle-double-right"></i></a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr class="bg-black">
                        <th class="text-center text-uppercase text-strong" colspan="7"><?php echo $this->getMes($mes) ?></th>
                    </tr>
                    <tr>
                        <th class="text-center text-uppercase bg-success text-strong">Dom</th>
                        <th class="text-center text-uppercase bg-success text-strong">Seg</th>
                        <th class="text-center text-uppercase bg-success text-strong">Ter</th>
                        <th class="text-center text-uppercase bg-success text-strong">Qua</th>
                        <th class="text-center text-uppercase bg-success text-strong">Qui</th>
                        <th class="text-center text-uppercase bg-success text-strong">Sex</th>
                        <th class="text-center text-uppercase bg-success text-strong">Sab</th>
                    </tr>
                    <?php for ($l = 0; $l < $linhas; $l++) : ?>
                        <tr>
                            <?php for ($q = 0; $q < 7; $q++) : ?>
                                <?php
                                $t = strtotime(($q + ($l * 7)) . ' days', strtotime($data_inicio));
                                $diaAtributo = $this->getDiaSemana($t);
                                $w = date('Y-m-d', $t);
                                ?>
                                <td><?php
                                    echo '<div class="text-right">' . date('d/m', $t) . '</div></br>';
                                    $w = strtotime($w);
                                    if (is_array($lista)) {
                                        foreach ($lista as $item) {
                                            if (isset($item[$diaAtributo]) && $item[$diaAtributo] == 1) {
                                                $dr_inicio = strtotime($item['data_inicial']);
                                                $dr_fim = strtotime($item['data_final']);

                                                if ($w >= $dr_inicio && $w <= $dr_fim) {
                                                    echo '<div tabindex="0" class="' . $this->getCategoriaReserva($item['categoria']) . ' labs lab_' . $item['id_equipamento'] . '" role="button" data-toggle="popover" data-trigger="focus" title="' . $item['categoria'] . ': <b>' . $item['nome'] . ' ' . $item['sobrenome'] . '</b>';
                                                    echo !empty($item['curso']) ? ' <br/><small>' . $item['curso'] : '';
                                                    echo '</small>" data-content="<b>' . $item['lab_nome'] . '</b>';
                                                    if ($item['categoria'] == 'Professor(a)') {
                                                        echo !empty($item['turma']) ? '<br/><b>Turma: </b>' . $item['turma'] : '';
                                                        echo !empty($item['disciplina']) ? '<br/><b>Disc.: </b>' . $item['disciplina'] : '';
                                                    }
                                                    echo '</br><b>De: </b>' . $this->formatDateViewComplete($item['data_inicial']) . '<br/><b>Até: </b>' . $this->formatDateViewComplete($item['data_final']) . '<br/><b>Horário: </b>' . $this->formatTimeView($item['horario_inicial']) . ' as ' . $this->formatTimeView($item['horario_final']) . '"><small>' . $this->formatTimeView($item['horario_inicial']) . ' - ' . $this->formatTimeView($item['horario_final']) . ' ';
                                                    echo !empty($item['turma']) ? $item['turma'] : $item['nome'];
                                                    echo ' <i class="fas fa-share-square"></i> </small> </div>';
                                                }
                                            }
                                        }
                                    }
                                    ?></td>
                            <?php endfor; ?>
                        </tr>
                    <?php endfor; ?>
                </table>
            </div>
        </div>
        <!--<div class="col-xs-12">-->
    </div> <!-- fim row-->
</div>