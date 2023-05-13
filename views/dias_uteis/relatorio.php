<div  class="container-fluid" id="section-relatorio_reserva">
    <div class="row" >
        <div class="col-sm-12 col-md-12 col-lg-12" id="pagina-header">
            <h3>Dias úteis para reserva dos equipamentos</h3>
        </div>
    </div>
    <!--FIM pagina-header-->
    <?php if ((isset($erro['msg']))) { ?>
        <div class="col-xs-12">
            <div class="alert <?php echo (isset($erro['class'])) ? $erro['class'] : 'alert-warning'; ?> " role="alert" id="alert-msg">
                <button class="close" data-hide="alert">&times;</button>
                <div id="resposta"><?php echo (isset($erro['msg'])) ? $erro['msg'] : '<i class = "fa fa-info-circle" aria-hidden = "true"></i> Preencha os campos corretamente.'; ?></div>
            </div>
        </div>
    <?php } ?>
    <div class="row">
        <?php
        if (!empty($dias)) {
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
                                <th>Categoria</th>
                                <th>Periodo Minimo</th>
                                <th>Periodo Maximo</th>
                                <?php if ($this->checkNivel()) : ?>
                                    <th class="table-acao" width="80px">Ação</th>
                                <?php endif; ?>
                            </tr>
                            <?php
                            $qtd = 1;
                            foreach ($dias as $indice):
                                ?>
                                <tr>
                                    <td><?php echo $qtd ?></td>
                                    <td><?php echo!empty($indice['categoria']) ? $indice['categoria'] : '' ?></td>
                                    <td><?php echo!empty($indice['minimo']) ? $indice['minimo'] . ' úteis' : '' ?> </td>
                                    <td><?php echo!empty($indice['maximo']) ? $indice['maximo'] . ' úteis' : '' ?></td>
                                    <?php if ($this->checkNivel()) : ?>
                                        <td class="table-acao text-center">
                                            <button type="button"  class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal_relatorio_<?php echo md5($indice['id']) ?>" title="Editar"><i class="fa fa-pencil-alt"></i></button>
                                        </td>
                                    <?php endif; ?>
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
</div>

<?php
if (isset($dias) && is_array($dias)) :
    foreach ($dias as $indice) :
        ?>        
        <!--MODAL - ESTRUTURA BÁSICA-->
        <section class="modal fade" id="modal_relatorio_<?php echo md5($indice['id']) ?>" tabindex="-1" role="dialog">
            <article class="modal-dialog modal-md" role="document">
                <section class="modal-content">
                    <header class="modal-header bg-primary">
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4>Deseja editar este registro?</h4>
                    </header>
                    <form method="POST">
                        <input type="hidden" name="nCod" value="<?php echo!empty($indice['id']) ? $indice['id'] : 0; ?>"/>
                        <article class="modal-body">
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label for="iCategoria">Categoria: </label>
                                    <input type="text" class="form-control" name="nCategoria" id="iCategoria" value="<?php echo (!empty($indice['categoria'])) ? $indice['categoria'] : ''; ?>"  disabled="disabled"/>
                                </div>
                                <div class="col-xs-12 form-group">
                                    <label for="iMin">Quantidade minima de dias úteis: *</label>
                                    <input type="text" class="form-control" name="nMin" id="iMin" value="<?php echo (!empty($indice['minimo'])) ? $indice['minimo'] : ''; ?>"/>
                                </div>
                                <div class="col-xs-12 form-group">
                                    <label for="iMax">Quantidade maxima de dias úteis: *</label>
                                    <input type="text" class="form-control" name="nMax" id="iMax" value="<?php echo (!empty($indice['maximo'])) ? $indice['maximo'] : ''; ?>"/>
                                </div>
                            </div>
                        </article>
                        <footer class="modal-footer">
                            <button type="submit" class="btn btn-success pull-left" name="nSalvar" value="Salvar"><i class="fa fa-check-circle" aria-hidden="true"></i> Salvar Alteração</button>
                            <button class="btn btn-default" type="button" data-dismiss="modal"><i class="fa fa-times"></i> Fechar</button>
                        </footer>
                    </form>
                </section>
            </article>
        </section>
        <?php
    endforeach;
endif;
?>