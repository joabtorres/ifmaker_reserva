<?php

/**
 *  classe "relatorioController"' é responsável para fazer o gerenciamento na dos relatorios usuários, adminstrador, equipamento, horario, reserva
 * 
 * @author Joab Torres <joabtorres1508@gmail.com>
 * @version 1.0
 * @copyright  (c) 2023, Joab Torres Alencar - Analista de Sistemas 
 * @access public
 * @package controllers
 * @example classe relatorioController
 */
class relatorioController extends controller
{

    /**
     * VIEW DE RELATORIO DE : RESEVAR
     *
     * @param integer $page
     * @return void
     */
    public function index(int $page = 1): void
    {
        $this->reserva($page);
    }

    /**
     * VIEW DE RELATORIO DE : RESERVA
     *
     * @param integer $page
     * @return void
     */
    public function reserva($page = 1)
    {
        if ($this->checkUser()) {
            $viewName = 'reserva/relatorio';
            $dados = array();
            $crudModel = new crud_db();
            if (!$this->checkNivel()) {
                $dados['usuarios'] = $crudModel->read("SELECT * FROM usuario WHERE status=1 AND id=:id ORDER BY nome, categoria ASC", array('id' => $this->getId()));
                $sql = 'SELECT u.categoria , u.nome, u.sobrenome, u.curso, r.*, l.nome as lab_nome FROM usuario as u INNER JOIN reserva as r ON u.id=r.id_usuario INNER JOIN equipamento as l ON r.id_equipamento=l.id WHERE r.id >0 AND u.id=' . $this->getId();
            } else {
                $dados['usuarios'] = $crudModel->read("SELECT * FROM usuario WHERE status=1 ORDER BY nome, categoria ASC");
                $sql = 'SELECT u.categoria , u.nome, u.sobrenome, u.curso, r.*, l.nome as lab_nome FROM usuario as u INNER JOIN reserva as r ON u.id=r.id_usuario INNER JOIN equipamento as l ON r.id_equipamento=l.id WHERE r.id >0 ';
            }
            $array = array();
            $parametro = '';
            if (isset($_GET['nBuscarBT'])) {
                $parametro = '?nInicio=' . $_GET['nInicio'] . '&nTermino=' . $_GET['nTermino'] . '&nStatus=' . $_GET['nStatus'] . '&nCategoria=' . $_GET['nCategoria'] . '&nUsuario=' . $_GET['nUsuario'] . '&nBuscarBT=BuscarBT';

                //data inicial
                if (!empty($_GET['nInicio']) && !empty($_GET['nTermino'])) {
                    $sql .= " AND  (r.data_inicial >=:data_inicial AND data_final <= :data_final) ";
                    $array['data_inicial'] = $this->formatDateBD(addslashes($_GET['nInicio']));
                    $array['data_final'] = $this->formatDateBD(addslashes($_GET['nTermino']));
                }

                if (!empty($_GET['nStatus'])) {
                    $sql = $sql . " AND r.status=:status";
                    switch ($_GET['nStatus']) {
                        case "Liberado":
                            $array['status'] = 1;
                            break;
                        case "Inativo":
                            $array['status'] = 0;
                            break;
                    }
                }
                //categoria
                if (!empty($_GET['nCategoria'])) {
                    $sql .= " AND  u.categoria=:categoria ";
                    $array['categoria'] = addslashes($_GET['nCategoria']);
                }
                if (!empty($_GET['nUsuario'])) {
                    $sql .= " AND  u.id=:id ";
                    $array['id'] = addslashes($_GET['nUsuario']);
                }
            }
            $limite = 30;
            $total_registro = $crudModel->read_specific("SELECT COUNT(id) AS qtd FROM reserva");
            $paginas = $total_registro['qtd'] / $limite;
            $indice = 0;
            $pagina_atual = (isset($page) && !empty($page)) ? addslashes($page) : 1;
            $indice = ($pagina_atual - 1) * $limite;
            $dados["paginas"] = $paginas;
            $dados["pagina_atual"] = $pagina_atual;
            $dados['metodo_buscar'] = $parametro;
            $sql .= " ORDER BY r.id DESC LIMIT $indice,$limite";
            $dados['reservas'] = $crudModel->read($sql, $array);
            $this->loadTemplate($viewName, $dados);
        } else {
            $url = "Location: " . BASE_URL . "login";
            header($url);
        }
    }
    /**
     * VIEW DE RELATORIO DE : dias uteis
     *
     * @param integer $page
     * @return void
     */
    public function dias_uteis(int $page = 1): void
    {
        if ($this->checkUser() && $this->checkNivel()) {
            $viewName = 'dias_uteis/relatorio';
            $dados = array();
            $crudModel = new crud_db();
            if (isset($_POST['nSalvar'])) {
                if (!empty($_POST['nMin']) && !empty($_POST['nMax'])) {
                    $array = array();
                    $array['id'] = addslashes($_POST['nCod']);
                    $array['minimo'] = addslashes($_POST['nMin']);
                    $array['maximo'] = addslashes($_POST['nMax']);
                    if ($crudModel->update("UPDATE dias_uteis SET minimo=:minimo, maximo=:maximo WHERE id=:id", $array)) {
                        $dados['erro'] = array('class' => 'alert-success', 'msg' => '<i class="fas fa-check-double"></i> Alteração realizada com sucesso!');
                    }
                } else {
                    $dados['erro'] = array('class' => 'alert-danger', 'msg' => '<i class="fa fa-times"></i> Preenchar os campos obrigatórios.');
                }
            }
            $sql = 'SELECT * FROM dias_uteis';
            $dados['dias'] = $crudModel->read($sql);
            $this->loadTemplate($viewName, $dados);
        } else {
            $url = "Location: " . BASE_URL . "login";
            header($url);
        }
    }
    /**
     * VIEW DE RELATORIO DE : horario
     *
     * @param integer $page
     * @return void
     */
    public function horario(int $page = 1): void
    {
        if ($this->checkUser() && $this->checkNivel()) {
            $viewName = 'horario/relatorio';
            $dados = array();
            $crudModel = new crud_db();
            $dados['labs'] = $crudModel->read("SELECT * FROM equipamento ORDER BY nome ASC");
            $sql = 'SELECT h.*, l.nome FROM horario AS h INNER JOIN equipamento AS l ON h.id_equipamento=l.id WHERE h.id >0 ';
            $array = array();
            $parametro = '';
            if (isset($_GET['nBuscarBT'])) {
                $parametro = '?&nStatus=' . $_GET['nStatus'] . '&nEquipamento=' . $_GET['nEquipamento'] . '&nBuscarBT=BuscarBT';
                if (!empty($_GET['nStatus'])) {
                    $sql = $sql . " AND h.status=:status";
                    switch ($_GET['nStatus']) {
                        case "Disponível":
                            $array['status'] = 1;
                            break;
                        case "Indisponível":
                            $array['status'] = 0;
                            break;
                    }
                }
                if (!empty($_GET['nEquipamento'])) {
                    $sql .= " AND  l.id=:id ";
                    $array['id'] = addslashes($_GET['nEquipamento']);
                }
            }
            $limite = 30;
            $total_registro = $crudModel->read_specific("SELECT COUNT(id) AS qtd FROM horario");
            $paginas = $total_registro['qtd'] / $limite;
            $indice = 0;
            $pagina_atual = (isset($page) && !empty($page)) ? addslashes($page) : 1;
            $indice = ($pagina_atual - 1) * $limite;
            $dados["paginas"] = $paginas;
            $dados["pagina_atual"] = $pagina_atual;
            $dados['metodo_buscar'] = $parametro;
            $sql .= " ORDER BY h.id DESC LIMIT $indice,$limite";
            $dados['horarios'] = $crudModel->read($sql, $array);
            $this->loadTemplate($viewName, $dados);
        } else {
            $url = "Location: " . BASE_URL . "login";
            header($url);
        }
    }
    /**
     * VIEW DE RELATORIO DE : equipamento
     *
     * @param integer $page
     * @return void
     */
    public function equipamento(int $page = 1): void
    {
        if ($this->checkUser() && $this->checkNivel()) {
            $viewName = 'equipamento/relatorio';
            $dados = array();
            $crudModel = new crud_db();
            $dados['labs'] = $crudModel->read("SELECT * FROM equipamento ORDER BY nome ASC");
            $sql = 'SELECT * FROM equipamento as l WHERE l.id >0 ';
            $array = array();
            $parametro = '';
            if (isset($_GET['nBuscarBT'])) {
                $parametro = '?&nStatus=' . $_GET['nStatus'] . '&nequipamento=' . $_GET['nequipamento'] . '&nBuscarBT=BuscarBT';


                if (!empty($_GET['nStatus'])) {
                    $sql = $sql . " AND l.status=:status";
                    switch ($_GET['nStatus']) {
                        case "Disponível":
                            $array['status'] = 1;
                            break;
                        case "Indisponível":
                            $array['status'] = 0;
                            break;
                    }
                }
                if (!empty($_GET['nequipamento'])) {
                    $sql .= " AND  l.id=:id ";
                    $array['id'] = addslashes($_GET['nequipamento']);
                }
            }
            $limite = 30;
            $total_registro = $crudModel->read_specific("SELECT COUNT(id) AS qtd FROM equipamento");
            $paginas = $total_registro['qtd'] / $limite;
            $indice = 0;
            $pagina_atual = (isset($page) && !empty($page)) ? addslashes($page) : 1;
            $indice = ($pagina_atual - 1) * $limite;
            $dados["paginas"] = $paginas;
            $dados["pagina_atual"] = $pagina_atual;
            $dados['metodo_buscar'] = $parametro;
            $sql .= " ORDER BY l.nome ASC LIMIT $indice,$limite";
            $dados['equipamentos'] = $crudModel->read($sql, $array);
            $this->loadTemplate($viewName, $dados);
        } else {
            $url = "Location: " . BASE_URL . "login";
            header($url);
        }
    }

    /**
     * VIEW RELATORIO USUARIO
     *
     * @param integer $page
     * @return void
     */
    public function usuario(int $page = 1): void
    {
        if ($this->checkUser() && $this->checkNivel()) {
            $viewName = 'usuario/relatorio';
            $dados = array();
            $crudModel = new crud_db();
            $dados['users'] = $crudModel->read("SELECT * FROM usuario ORDER BY nome, categoria ASC");
            $sql = 'SELECT * FROM usuario WHERE id >0 ';
            $array = array();
            $parametro = '';
            if (isset($_GET['nBuscarBT'])) {
                $parametro = '?&nStatus=' . $_GET['nStatus'] . '&nCategoria=' . $_GET['nCategoria'] . '&nUsuario=' . $_GET['nUsuario'] . '&nBuscarBT=BuscarBT';

                if (!empty($_GET['nStatus'])) {
                    $sql = $sql . " AND status=:status";
                    switch ($_GET['nStatus']) {
                        case "Disponível":
                            $array['status'] = 1;
                            break;
                        case "Indisponível":
                            $array['status'] = 0;
                            break;
                    }
                }
                //categoria
                if (!empty($_GET['nCategoria'])) {
                    $sql .= " AND  categoria=:categoria ";
                    $array['categoria'] = addslashes($_GET['nCategoria']);
                }

                if (!empty($_GET['nUsuario'])) {
                    $sql .= " AND  id=:id ";
                    $array['id'] = addslashes($_GET['nUsuario']);
                }
            }
            $limite = 30;
            $total_registro = $crudModel->read_specific("SELECT COUNT(id) AS qtd FROM usuario");
            $paginas = $total_registro['qtd'] / $limite;
            $indice = 0;
            $pagina_atual = (isset($page) && !empty($page)) ? addslashes($page) : 1;
            $indice = ($pagina_atual - 1) * $limite;
            $dados["paginas"] = $paginas;
            $dados["pagina_atual"] = $pagina_atual;
            $dados['metodo_buscar'] = $parametro;
            $sql .= " ORDER BY id DESC LIMIT $indice,$limite";
            $dados['usuarios'] = $crudModel->read($sql, $array);
            $this->loadTemplate($viewName, $dados);
        } else {
            $url = "Location: " . BASE_URL . "login";
            header($url);
        }
    }

    /**
     * VIEW RELATORIO ADMINSTRADOR
     *
     * @param integer $page
     * @return void
     */
    public function administrador(int $page = 1): void
    {
        if ($this->checkUser() && $this->checkNivel()) {
            $viewName = 'administrador/relatorio';
            $dados = array();
            $admModel = new administrador();
            $dados['users'] = $admModel->read("SELECT * FROM administrador ORDER BY nome ASC");
            $sql = 'SELECT * FROM administrador WHERE id >0 ';
            $array = array();
            $parametro = '';
            if (isset($_GET['nBuscarBT'])) {
                $parametro = '?&nStatus=' . $_GET['nStatus'] . '&nUsuario=' . $_GET['nUsuario'] . '&nBuscarBT=BuscarBT';

                if (!empty($_GET['nStatus'])) {
                    $sql = $sql . " AND status=:status";
                    switch ($_GET['nStatus']) {
                        case "Disponível":
                            $array['status'] = 1;
                            break;
                        case "Indisponível":
                            $array['status'] = 0;
                            break;
                    }
                }
                //categoria
                if (!empty($_GET['nCategoria'])) {
                    $sql .= " AND  cargo=:cargo ";
                    $array['cargo'] = addslashes($_GET['nCategoria']);
                }
                if (!empty($_GET['nUsuario'])) {
                    $sql .= " AND  id=:id ";
                    $array['id'] = addslashes($_GET['nUsuario']);
                }
            }
            $limite = 30;
            $total_registro = $admModel->read_specific("SELECT COUNT(id) AS qtd FROM administrador");
            $paginas = $total_registro['qtd'] / $limite;
            $indice = 0;
            $pagina_atual = (isset($page) && !empty($page)) ? addslashes($page) : 1;
            $indice = ($pagina_atual - 1) * $limite;
            $dados["paginas"] = $paginas;
            $dados["pagina_atual"] = $pagina_atual;
            $dados['metodo_buscar'] = $parametro;
            $sql .= " ORDER BY id DESC LIMIT $indice,$limite";
            $dados['usuarios'] = $admModel->read($sql, $array);
            $this->loadTemplate($viewName, $dados);
        } else {
            $url = "Location: " . BASE_URL . "login";
            header($url);
        }
    }
}
