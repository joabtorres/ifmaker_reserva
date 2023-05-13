<?php

/**
 * A classe 'homeController' é responsável para fazer o carregamento da página home do sistema
 * 
 * @author Joab Torres <joabtorres1508@gmail.com>
 * @version 1.0
 * @copyright  (c) 2017, Joab Torres Alencar - Analista de Sistemas 
 * @access public
 * @package controllers
 * @example classe homeController
 */
class homeController extends controller
{

    /**
     * Está função pertence a uma action do controle MVC, ela é responśavel por carrega a view  presente no diretorio views/home.php, desde que o usuário esteja logado no sistema
     * @access public
     * @author Joab Torres <joabtorres1508@gmail.com>
     */
    public function index($mes = array(), $ano = array())
    {
        $viewName = "home";
        $dados = array();
        $crudModel = new crud_db();
        if (isset($mes) && !empty($mes) && isset($ano) && !empty($ano)) {
            $mes = $mes;
            $ano = $ano;
            $data = $ano . '-' . $mes;
        } else {
            $mes = date('m');
            $ano = date("Y");
            $data = $ano . '-' . $mes;
        }

        $dia1 = date('w', strtotime($data));
        $dias = date('t', strtotime($data));
        $linhas = ceil(($dia1 + $dias) / 7);
        $dia1 = -$dia1;

        $data_inicio = date('Y-m-d', strtotime($dia1 . ' days', strtotime($data)));
        $data_fim = date('Y-m-d', strtotime((($dia1 + ($linhas * 7) - 1)) . ' days', strtotime($data)));
        $dados['linhas'] = $linhas;
        $dados['data_inicio'] = $data_inicio;
        $dados['data_fim'] = $data_fim;
        $array = array();
        $array['data_inicial'] = $data_inicio;
        $array['data_final'] = $data_fim;
        $dados['mes'] = $mes;
        $dados['ano'] = $ano;
        $dados['lista'] = $crudModel->read('SELECT u.categoria , u.nome, u.sobrenome, u.curso, r.*, l.nome as lab_nome FROM usuario as u INNER JOIN reserva as r ON u.id=r.id_usuario INNER JOIN equipamento as l ON r.id_equipamento=l.id WHERE r.status=1 AND ( NOT ( r.data_inicial > :data_final OR data_final < :data_inicial ) ) ORDER BY r.horario_inicial ASC', $array);
        $this->loadTemplate($viewName, $dados);
    }

    /*     * *
      função destinada a desconetar o usuário
     * * */

    public function sair()
    {
        $this->logout();
    }
}
