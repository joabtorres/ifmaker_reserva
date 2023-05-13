<?php

/**
 * A classe 'cadastrarController' é responsável para fazer o gerenciamento no cadastro de usuários, adminstrador, equipamento, horario, reserva
 * 
 * @author Joab Torres <joabtorres1508@gmail.com>
 * @version 1.0
 * @copyright  (c) 2023 Joab Torres Alencar - Analista de Sistemas 
 * @access public
 * @package controllers
 * @example classe cadastrarController
 */
class cadastrarController extends controller
{

    /**
     * VIEW INDEX
     *
     * @return void
     */
    public function index(): void
    {
        $this->reserva();
    }
    /**
     * Função para retornar a lista de horários disponiveis para reserva
     *
     * @return void
     */
    public function getHorario(): void
    {
        if (isset($_POST) && $this->checkUser()) {
            $crud = new crud_db();
            $array = array('id_usuario' => filter_input(INPUT_POST, "id_usuario", FILTER_VALIDATE_INT), 'id_equip' => filter_input(INPUT_POST, "id_lab", FILTER_VALIDATE_INT));
            //$array = ['id_usuario' => 1, "id_equip" => 8];
            $resultado = $crud->read("SELECT h.hora_inicial, h.hora_final, TIME_TO_SEC(h.hora_final) - TIME_TO_SEC(h.hora_inicial) as intervalo, u.categoria FROM horario as h INNER JOIN usuario AS u WHERE u.id=:id_usuario AND h.id_equipamento=:id_equip ORDER BY h.hora_inicial ASC", $array);
            echo !empty($resultado) ? json_encode($resultado) : '';
        }
    }

    /**
     * VIEW CADASTRO RESERVA
     *
     * @return void
     */
    public function reserva(): void
    {
        if ($this->checkUser()) {
            //verifica se é usuário
            if ($this->getCategoria() == "Usuario") {
                $url = "Location: " . BASE_URL . "home";
                header($url);
            }
            $viewName = 'reserva/cadastro';
            $dados = array();
            $crudModel = new crud_db();
            $minimo = 0;
            $maximo = 0;
            //seleciona os usuários
            if ($this->checkNivel()) {
                $dados['usuarios'] = $crudModel->read("SELECT * FROM usuario WHERE status=1 ORDER BY nome, categoria ASC");
            } else {
                $dados['usuarios'] = $crudModel->read("SELECT * FROM usuario WHERE status=1 AND id=:id ORDER BY nome, categoria ASC", array('id' => $this->getId()));
                $dias_uteis = $crudModel->read_specific("SELECT * FROM dias_uteis WHERE categoria=:categoria", array('categoria' => $this->getCategoria()));
                if ($crudModel->getNumRows()) {
                    $minimo = $this->getdiasUteis($dias_uteis['minimo']);
                    $maximo = $this->getdiasUteis($dias_uteis['maximo'] + $dias_uteis['minimo'] - 1);
                }
            }
            echo ' <script>minDate = ' . $minimo . '; maxDate=' . $maximo . ';</script>';
            //lista os equipamentos disponíveis
            $dados['equipamentos'] = $crudModel->read("SELECT * FROM equipamento WHERE status=1 ORDER BY nome ASC");
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $requisicao = md5(implode($_POST));

                if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $requisicao) {
                    $url = "Location: " . BASE_URL . "cadastrar/reserva";
                    header($url);
                } else {
                    $_SESSION['last_request'] = $requisicao;
                    if (isset($_POST['nSalvar'])) {
                        $reserva = array();
                        //nome
                        if (!empty($_POST['nUsuario'])) {
                            $reserva['id_usuario'] = filter_input(INPUT_POST, "nUsuario", FILTER_VALIDATE_INT);
                        } else {
                            $dados['reserva_erro']['id_usuario']['msg'] = 'Informe o usuário';
                            $dados['reserva_erro']['id_usuario']['class'] = 'has-error';
                        }
                        //laboratorio
                        if (!empty($_POST['nEquipamento'])) {
                            $reserva['id_equipamento'] = filter_input(INPUT_POST, "nEquipamento", FILTER_VALIDATE_INT);
                        } else {
                            $dados['reserva_erro']['id_equipamento']['msg'] = 'Informe o equipamento';
                            $dados['reserva_erro']['id_equipamento']['class'] = 'has-error';
                        }
                        //data_inicial
                        if (!empty($_POST['data_inicial'])) {
                            $reserva['data_inicial'] = $this->formatDateBD(filter_input(INPUT_POST, "data_inicial"));
                        } else {
                            $dados['reserva_erro']['data_inicial']['msg'] = 'Informe a data';
                            $dados['reserva_erro']['data_inicial']['class'] = 'has-error';
                        }
                        //data_final
                        if (!empty($_POST['data_final'])) {
                            $reserva['data_final'] = $this->formatDateBD(filter_input(INPUT_POST, "data_final"));
                        } else {
                            $dados['reserva_erro']['data_final']['msg'] = 'Informe a data';
                            $dados['reserva_erro']['data_final']['class'] = 'has-error';
                        }
                        //validar data;
                        $data_inicial = 0;
                        $data_final = 0;
                        if (isset($reserva['data_inicial']) && isset($reserva['data_final'])) {
                            $data_inicial = strtotime($reserva['data_inicial']);
                            $data_final = strtotime($reserva['data_final']);

                            if ($this->checkNivel() == false) {
                                $data_atual = strtotime(date('Y-m-d'));
                                if (($data_inicial < strtotime('+' . $minimo . ' day', $data_atual)) || ($data_inicial > strtotime('+' . $maximo . ' day', strtotime(date('Y-m-d'))))) {
                                    $d1 = strtotime('+' . $minimo . ' day', $data_atual);
                                    $d2 = strtotime('+' . $maximo . ' day', $data_atual);
                                    $dados['reserva_erro']['data_inicial']['msg'] = 'Infome a data entre ' . date("d/m/Y", $d1) . ' a ' . date("d/m/Y", $d2);
                                    $dados['reserva_erro']['data_inicial']['class'] = 'has-error';
                                }
                                if (($data_final < strtotime('+' . $minimo . ' day', strtotime(date('Y-m-d')))) || ($data_final > strtotime('+' . $maximo . ' day', strtotime(date('Y-m-d'))))) {
                                    $d1 = strtotime('+' . $minimo . ' day', $data_atual);
                                    $d2 = strtotime('+' . $maximo . ' day', $data_atual);
                                    $dados['reserva_erro']['data_final']['msg'] = 'Infome a data entre ' . date("d/m/Y", $d1) . ' a ' . date("d/m/Y", $d2);
                                    $dados['reserva_erro']['data_final']['class'] = 'has-error';
                                }
                                //aluno não pode marcar para final de semana
                                if ($this->getCategoria() == "Aluno(a)") {
                                    if (($this->getDiaSemana($data_inicial) == 'sabado') || ($this->getDiaSemana($data_inicial) == 'domingo')) {
                                        $dados['reserva_erro']['data_inicial']['msg'] = 'Não é reservado o equipamento aos finais da semana.';
                                        $dados['reserva_erro']['data_inicial']['class'] = 'has-error';
                                    }
                                    if (($this->getDiaSemana($data_final) == 'sabado') || ($this->getDiaSemana($data_final) == 'domingo')) {
                                        $dados['reserva_erro']['data_final']['msg'] = 'Não é reservado o equipamento aos finais da semana.';
                                        $dados['reserva_erro']['data_final']['class'] = 'has-error';
                                    }
                                }
                            }
                            if ($data_inicial > $data_final) {
                                $dados['reserva_erro']['data_final']['msg'] = 'A data final deve ser maior ou igual ao campo da data inicial';
                                $dados['reserva_erro']['data_final']['class'] = 'has-error';
                                $dados['reserva_erro']['data_inicial']['msg'] = 'A data final deve ser maior ou igual ao campo da data inicial';
                                $dados['reserva_erro']['data_inicial']['class'] = 'has-error';
                            }
                        }

                        //nHorario
                        if (!empty($_POST['nHorario'])) {
                            $array = explode(" - ", $_POST['nHorario']);
                            $reserva['horario_inicial'] = $array[0];
                            $reserva['horario_final'] = $array[1];
                        } else {
                            $dados['reserva_erro']['horario']['msg'] = 'Selecione o horário disponível';
                            $dados['reserva_erro']['horario']['class'] = 'has-error';
                        }

                        if ($data_final > $data_inicial) {
                            if (isset($_POST['nSegunda']) || isset($_POST['nTerca']) || isset($_POST['nQuarta']) || isset($_POST['nQuinta']) || isset($_POST['nSexta']) || isset($_POST['nSabado'])) {
                                $reserva['segunda'] = !empty($_POST['nSegunda']) ? 1 : 0;
                                $reserva['terca'] = !empty($_POST['nTerca']) ? 1 : 0;
                                $reserva['quarta'] = !empty($_POST['nQuarta']) ? 1 : 0;
                                $reserva['quinta'] = !empty($_POST['nQuinta']) ? 1 : 0;
                                $reserva['sexta'] = !empty($_POST['nSexta']) ? 1 : 0;
                                $reserva['sabado'] = !empty($_POST['nSabado']) ? 1 : 0;
                            } else {
                                $dados['reserva_erro']['dias']['msg'] = 'Selecione os dias da semana';
                                $dados['reserva_erro']['dias']['class'] = 'has-error';
                            }
                        } else if ($data_inicial > 0) {
                            $diasemana = array('segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado');
                            $dia = $this->getDiaSemana($data_inicial);
                            for ($i = 0; $i < count($diasemana); $i++) {
                                if ($dia == $diasemana[$i]) {
                                    $reserva[$diasemana[$i]] = 1;
                                } else {
                                    $reserva[$diasemana[$i]] = 0;
                                }
                            }
                        }

                        //Turma
                        $reserva['turma'] = isset($_POST['nTurma']) ? filter_input(INPUT_POST, "nTurma", FILTER_SANITIZE_SPECIAL_CHARS) : '';
                        //disciplina
                        $reserva['disciplina'] = isset($_POST['nDisciplina']) ? filter_input(INPUT_POST, "nDisciplina", FILTER_SANITIZE_SPECIAL_CHARS) : '';
                        //status
                        $reserva['status'] = ($this->checkNivel()) ? 1 : 0;

                        if (isset($dados['reserva_erro']) && !empty($dados['reserva_erro'])) {
                            $dados['erro']['msg'] = '<i class="fa fa-info-circle" aria-hidden="true"></i> Preencha todos os campos corretamente.';
                            $dados['erro']['class'] = 'alert-danger';
                            $dados['reserva'] = $reserva;
                        } else {
                            $sql = "SELECT COUNT(id) as qtd, id_usuario FROM reserva WHERE id_equipamento = '" . $reserva['id_equipamento'] . "' AND (NOT ( data_inicial > '" . $reserva["data_final"] . "' OR data_final < '" . $reserva["data_inicial"] . "' )) AND (horario_inicial BETWEEN '" . $reserva['horario_inicial'] . "' AND '" . $reserva['horario_final'] . "' OR horario_final BETWEEN '" . $reserva['horario_inicial'] . "' AND '" . $reserva['horario_final'] . "')";
                            $array = array('segunda' => $reserva['segunda'], 'terca' => $reserva['terca'], 'quarta' => $reserva['quarta'], 'quinta' => $reserva['quinta'], 'sexta' => $reserva['sexta'], 'sabado' => $reserva['sabado']);
                            $d = false;
                            foreach ($array as $indice => $key) {
                                if ($key == 1) {
                                    if ($d == true) {
                                        $sql .= " OR $indice=" . $key;
                                    } else {
                                        $d = true;
                                        $sql .= " AND ($indice=" . $key;
                                    }
                                }
                            }
                            if ($d == true) {
                                $sql .= " )";
                            }
                            $resultado = $crudModel->read_specific($sql);
                            if ($resultado['qtd'] > 0) {
                                $user2 = $crudModel->read_specific('SELECT * FROM usuario WHERE id=' . $resultado['id_usuario']);
                                //verifica que categoria é 
                                if ($user2['categoria'] == "Aluno(a)") {
                                    $sql = "SELECT COUNT(*) as qtd, r.id_usuario FROM reserva AS r INNER JOIN usuario AS u ON r.id_usuario=u.id WHERE r.id_usuario=" . $reserva['id_usuario'] . " AND (NOT ( data_inicial > '" . $reserva["data_final"] . "' OR data_final < '" . $reserva["data_inicial"] . "' )) ";
                                    $resultado = $crudModel->read_specific($sql);
                                }
                                if ($resultado['qtd'] > 0) {
                                    $user = $crudModel->read_specific('SELECT * FROM usuario WHERE id=' . $reserva['id_usuario']);
                                    if (($user2['categoria'] == $user['categoria']) && ($user2['categoria'] == "Aluno(a)")) {
                                        //se for aluno, verifica quantidade de registro
                                        $sql = "SELECT COUNT(*) as qtd FROM reserva AS r INNER JOIN usuario AS u ON r.id_usuario=u.id WHERE r.id_usuario=" . $user['id'] . " AND (NOT ( data_inicial > '" . $reserva["data_final"] . "' OR data_final < '" . $reserva["data_inicial"] . "' )) AND (horario_inicial BETWEEN '" . $reserva['horario_inicial'] . "' AND '" . $reserva['horario_final'] . "' OR horario_final BETWEEN '" . $reserva['horario_inicial'] . "' AND '" . $reserva['horario_final'] . "')";
                                        $result = $crudModel->read_specific($sql);
                                        if (intval($result['qtd']) >= 2) {
                                            $dados['erro']['msg'] = '<i class="fa fa-info-circle" aria-hidden="true"></i> Só pode reserva o equipamento duas vezes no mesmo periodo.';
                                            $dados['erro']['class'] = 'alert-danger';
                                            $_POST = array();
                                        }
                                    } else {
                                        $dados['erro']['msg'] = '<i class="fa fa-info-circle" aria-hidden="true"></i> Já possui uma reserva agendada para este periodo, dia e horário.';
                                        $dados['erro']['class'] = 'alert-danger';
                                        $_POST = array();
                                    }
                                }
                            }

                            if (empty($dados['erro'])) {
                                $sql = "INSERT INTO reserva(id_usuario, id_equipamento, data_inicial, data_final, horario_inicial, horario_final, turma, disciplina, segunda, terca, quarta, quinta, sexta, sabado, status) VALUES (:id_usuario, :id_equipamento, :data_inicial, :data_final, :horario_inicial, :horario_final, :turma, :disciplina, :segunda, :terca, :quarta, :quinta, :sexta, :sabado, :status)";
                                $cadastro = $crudModel->create($sql, $reserva);
                                if ($cadastro) {
                                    $dados['erro']['msg'] = '<i class="fa fa-check" aria-hidden="true"></i> Cadastro realizado com sucesso!';
                                    $dados['erro']['class'] = 'alert-success';
                                    $_POST = array();
                                }
                            }
                        }
                    }
                }
            }

            $this->loadTemplate($viewName, $dados);
        } else {
            $url = "Location: " . BASE_URL . "login";
            header($url);
        }
    }

    /**
     * VIEW CADASTRAR HORARIO
     *
     * @return void
     */
    public function horario(): void
    {
        if ($this->checkUser() && $this->checkNivel()) {
            $viewName = 'horario/cadastrar';
            $dados = array();
            $crudModel = new crud_db();
            $dados['equipamentos'] = $crudModel->read("SELECT * FROM equipamento ORDER BY nome ASC");
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $requisicao = md5(implode($_POST));

                if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $requisicao) {
                    $url = "Location: " . BASE_URL . "cadastrar/horario";
                    header($url);
                } else {
                    $_SESSION['last_request'] = $requisicao;
                    if (isset($_POST['nSalvar'])) {
                        if (!empty($_POST['hora_inicial']) && !empty($_POST['hora_final'])) {
                            $horario = array();
                            $horario['id_equipamento'] = filter_input(INPUT_POST, "nEquipamento", FILTER_SANITIZE_SPECIAL_CHARS);
                            $horario['hora_inicial'] = filter_input(INPUT_POST, "hora_inicial", FILTER_SANITIZE_SPECIAL_CHARS);
                            $horario['hora_final'] = filter_input(INPUT_POST, "hora_final", FILTER_SANITIZE_SPECIAL_CHARS);
                            $horario['status'] = filter_input(INPUT_POST, "nStatus", FILTER_SANITIZE_SPECIAL_CHARS) == "Disponível" ? 1 : 0;
                            $resultado = $crudModel->create("INSERT INTO horario (id_equipamento, hora_inicial, hora_final, status) VALUES (:id_equipamento, :hora_inicial, :hora_final, :status) ", $horario);
                            if ($resultado) {
                                $dados['erro'] = array('class' => 'alert-success', 'msg' => '<i class="fas fa-check-double"></i> Cadastro realizado com sucesso!');
                            } else {
                                $dados['horario'] = $horario;
                            }
                        } else {
                            $dados['erro'] = array('class' => 'alert-danger', 'msg' => '<i class="fa fa-times"></i> Preenchar os campos obrigatórios.');
                        }
                    }
                }
            }

            $this->loadTemplate($viewName, $dados);
        } else {
            $url = "Location: " . BASE_URL . "login";
            header($url);
        }
    }

    /**
     * VIEW CADASTRAR EQUIPAMENTO
     *
     * @return void
     */
    public function equipamento(): void
    {
        if ($this->checkUser() && $this->checkNivel()) {
            $viewName = 'equipamento/cadastrar';
            $dados = array();
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $requisicao = md5(implode($_POST));

                if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $requisicao) {
                    $url = "Location: " . BASE_URL . "cadastrar/equipamento";
                    header($url);
                } else {
                    $_SESSION['last_request'] = $requisicao;

                    if (isset($_POST['nSalvar'])) {
                        if (!empty($_POST['nNome']) && !empty($_POST['qtd'])) {
                            $arrayCad = array();
                            $arrayCad['nome'] = filter_input(INPUT_POST, "nNome", FILTER_SANITIZE_SPECIAL_CHARS);
                            $arrayCad['qtd'] = filter_input(INPUT_POST, "qtd", FILTER_SANITIZE_SPECIAL_CHARS);
                            $arrayCad['status'] = filter_input(INPUT_POST, "nStatus", FILTER_SANITIZE_SPECIAL_CHARS);
                            $crudModel = new crud_db();
                            $resultado = $crudModel->create("INSERT INTO equipamento (nome, qtd, status) VALUES (:nome, :qtd, :status)", $arrayCad);
                            if ($resultado) {
                                $dados['erro'] = array('class' => 'alert-success', 'msg' => '<i class="fas fa-check-double"></i> Cadastro realizado com sucesso!');
                            } else {
                                $dados['laboratorio'] = $arrayCad;
                            }
                        } else {
                            $dados['erro'] = array('class' => 'alert-danger', 'msg' => '<i class="fa fa-times"></i> Preenchar os campos obrigatórios.');
                        }
                    }
                }
            }

            $this->loadTemplate($viewName, $dados);
        } else {
            $url = "Location: " . BASE_URL . "login";
            header($url);
        }
    }




    /**
     * VIEW CADASTRAR USUARIO
     *
     * @return void
     */
    public function usuario(): void
    {
        if ($this->checkUser() && $this->checkNivel()) {
            $view = "usuario/cadastro";
            $dados = array();
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $requisicao = md5(implode($_POST));

                if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $requisicao) {
                    $url = "Location: " . BASE_URL . "cadastrar/usuario";
                    header($url);
                } else {
                    $_SESSION['last_request'] = $requisicao;
                    $userModel = new usuario();
                    //Array que vai armazena os dados do usuário;
                    $usuario = array();
                    if (isset($_POST['nSalvar'])) {
                        //nome
                        if (!empty(filter_input(INPUT_POST, "nNome", FILTER_SANITIZE_SPECIAL_CHARS))) {
                            $usuario['nome'] = filter_input(INPUT_POST, "nNome", FILTER_SANITIZE_SPECIAL_CHARS);
                        } else {
                            $dados['usuario_erro']['nome']['msg'] = 'Informe o nome';
                            $dados['usuario_erro']['nome']['class'] = 'has-error';
                        }
                        //sobrenome
                        if (!empty(filter_input(INPUT_POST, "nSobrenome", FILTER_SANITIZE_SPECIAL_CHARS))) {
                            $usuario['sobrenome'] = filter_input(INPUT_POST, "nSobrenome", FILTER_SANITIZE_SPECIAL_CHARS);
                        } else {
                            $dados['usuario_erro']['sobrenome']['msg'] = 'Informe o sobrenome';
                            $dados['usuario_erro']['sobrenome']['class'] = 'has-error';
                        }
                        //nMatricula
                        $matricula = filter_input(INPUT_POST, "nMatricula", FILTER_SANITIZE_SPECIAL_CHARS);
                        if (!empty($matricula)) {
                            $usuario['matricula'] = $matricula;
                            if ($userModel->read_specific('SELECT * FROM usuario WHERE matricula=:matricula', array('matricula' => $usuario['matricula']))) {
                                $dados['usuario_erro']['matricula']['msg'] = 'matricula já cadastrada';
                                $dados['usuario_erro']['matricula']['class'] = 'has-error';
                                $dados['erro']['msg'] = '<i class="fa fa-info-circle" aria-hidden="true"></i> Não é possível cadastrar uma matricula/Siape já cadastrada, por favor informe outra matricula/Siape';
                                $dados['erro']['class'] = 'alert-danger';
                                $usuario['matricula'] = null;
                            }
                        } else {
                            $dados['usuario_erro']['matricula']['msg'] = 'Informe a matricula/siape';
                            $dados['usuario_erro']['matricula']['class'] = 'has-error';
                        }
                        //email
                        $email = filter_input(INPUT_POST, "nEmail", FILTER_SANITIZE_SPECIAL_CHARS);
                        if (!empty($email)) {
                            $usuario['email'] = $email;
                            if ($userModel->read_specific('SELECT * FROM usuario WHERE email=:email', array('email' => $usuario['email']))) {
                                $dados['usuario_erro']['email']['msg'] = 'E-mail já cadastrado';
                                $dados['usuario_erro']['email']['class'] = 'has-error';
                                $dados['erro']['msg'] = '<i class="fa fa-info-circle" aria-hidden="true"></i> Não é possível cadastrar um e-mail já cadastrado, por favor informe outro endereço de e-mail';
                                $dados['erro']['class'] = 'alert-danger';
                                $usuario['email'] = null;
                            }
                        } else {
                            $dados['usuario_erro']['email']['msg'] = 'Informe o e-mail';
                            $dados['usuario_erro']['email']['class'] = 'has-error';
                        }

                        //CPF
                        $cpf = filter_input(INPUT_POST, "nCPF", FILTER_SANITIZE_SPECIAL_CHARS);
                        if (!empty($cpf)) {
                            $usuario['cpf'] = $cpf;
                            if ($userModel->read_specific('SELECT * FROM usuario WHERE cpf=:cpf', array('cpf' => $usuario['cpf']))) {
                                $dados['usuario_erro']['cpf']['msg'] = 'CPF já cadastrado';
                                $dados['usuario_erro']['cpf']['class'] = 'has-error';
                                $dados['erro']['msg'] = '<i class="fa fa-info-circle" aria-hidden="true"></i> Não é possível cadastrar um CPF já cadastrado';
                                $dados['erro']['class'] = 'alert-danger';
                                $usuario['cpf'] = null;
                            }
                        } else {
                            $dados['usuario_erro']['cpf']['msg'] = 'Informe o CPF';
                            $dados['usuario_erro']['cpf']['class'] = 'has-error';
                        }

                        //data de nascimento
                        $nascimento = filter_input(INPUT_POST, "nNascimento", FILTER_SANITIZE_SPECIAL_CHARS);
                        if (!empty($nascimento)) {
                            $usuario['nascimento'] = $this->formatDateBD($nascimento);
                        } else {
                            $dados['usuario_erro']['nascimento']['msg'] = 'Informe o nome';
                            $dados['usuario_erro']['nascimento']['class'] = 'has-error';
                        }
                        //senha
                        $senha = filter_input(INPUT_POST, "nSenha", FILTER_SANITIZE_SPECIAL_CHARS);
                        $rsenha = filter_input(INPUT_POST, "nRepetirSenha", FILTER_SANITIZE_SPECIAL_CHARS);
                        if (!empty($senha) && !empty($rsenha)) {
                            //senha
                            if ($senha == $rsenha) {
                                $usuario['senha'] = $senha;
                            } else {
                                $dados['usuario_erro']['senha']['msg'] = "Os campos 'Senha' e 'Repetir Senha' não estão iguais! ";
                                $dados['usuario_erro']['senha']['class'] = 'has-error';
                            }
                        } else {
                            $dados['usuario_erro']['senha']['msg'] = "Os campos 'Senha' e 'Repetir Senha' devem ser preenchidos";
                            $dados['usuario_erro']['senha']['class'] = 'has-error';
                        }
                        //nCategoria
                        $usuario['categoria'] = filter_input(INPUT_POST, "nCategoria", FILTER_SANITIZE_SPECIAL_CHARS);
                        //nCurso
                        $curso = filter_input(INPUT_POST, "nCurso", FILTER_SANITIZE_SPECIAL_CHARS);
                        if (!empty($curso)) {
                            $usuario['curso'] = $curso;
                        }
                        //sexo
                        $usuario['sexo'] = filter_input(INPUT_POST, "nSexo", FILTER_SANITIZE_SPECIAL_CHARS);

                        $usuario['status'] = (!empty(filter_input(INPUT_POST, "nStatus", FILTER_SANITIZE_SPECIAL_CHARS))) ? filter_input(INPUT_POST, "nStatus", FILTER_SANITIZE_SPECIAL_CHARS)  : 0;

                        //imagem
                        if (isset($_FILES['tImagem-1']) && $_FILES['tImagem-1']['error'] == 0) {
                            $usuario['imagem'] = $_FILES['tImagem-1'];
                        }
                        if (isset($dados['usuario_erro']) && !empty($dados['usuario_erro'])) {
                            $dados['erro']['msg'] = '<i class="fa fa-info-circle" aria-hidden="true"></i> Preencha todos os campos obrigatórios (*).';
                            $dados['erro']['class'] = 'alert-danger';
                            $dados['usuario'] = $usuario;
                        } else {
                            $userModel->create($usuario);
                            $dados['erro']['msg'] = '<i class="fa fa-check" aria-hidden="true"></i> Cadastro realizado com sucesso!';
                            $dados['erro']['class'] = 'alert-success';
                            $_POST = array();
                        }
                    }
                }
            }
            $this->loadTemplate($view, $dados);
        } else {
            $url = "Location: " . BASE_URL . "login";
            header($url);
        }
    }
    /**
     * VIEW CADASTRAR ADMINISTRADOR
     *
     * @return void
     */
    public function administrador()
    {
        if ($this->checkUser() && $this->checkNivel()) {
            $view = "administrador/cadastro";
            $dados = array();
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $requisicao = md5(implode($_POST));
                if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $requisicao) {
                    $url = "Location: " . BASE_URL . "cadastrar/administrador";
                    header($url);
                } else {
                    $_SESSION['last_request'] = $requisicao;
                    $admModel = new administrador();
                    //Array que vai armazena os dados do usuário;
                    $usuario = array();
                    if (filter_input(INPUT_POST, "nSalvar", FILTER_SANITIZE_SPECIAL_CHARS)) {
                        //nome
                        if (!empty(filter_input(INPUT_POST, "nNome", FILTER_SANITIZE_SPECIAL_CHARS))) {
                            $usuario['nome'] = addslashes(filter_input(INPUT_POST, "nNome", FILTER_SANITIZE_SPECIAL_CHARS));
                        } else {
                            $dados['usuario_erro']['nome']['msg'] = 'Informe o nome';
                            $dados['usuario_erro']['nome']['class'] = 'has-error';
                        }
                        //sobrenome
                        if (!empty(filter_input(INPUT_POST, "nSobrenome", FILTER_SANITIZE_SPECIAL_CHARS))) {
                            $usuario['sobrenome'] = filter_input(INPUT_POST, "nSobrenome", FILTER_SANITIZE_SPECIAL_CHARS);
                        } else {
                            $dados['usuario_erro']['sobrenome']['msg'] = 'Informe o sobrenome';
                            $dados['usuario_erro']['sobrenome']['class'] = 'has-error';
                        }
                        //nMatricula
                        if (!empty(filter_input(INPUT_POST, "nMatricula", FILTER_SANITIZE_SPECIAL_CHARS))) {
                            $usuario['matricula'] = addslashes($_POST['nMatricula']);
                            if ($admModel->read_specific('SELECT * FROM administrador WHERE matricula=:matricula', array('matricula' => $usuario['matricula']))) {
                                $dados['usuario_erro']['matricula']['msg'] = 'matricula já cadastrada';
                                $dados['usuario_erro']['matricula']['class'] = 'has-error';
                                $dados['erro']['msg'] = '<i class="fa fa-info-circle" aria-hidden="true"></i> Não é possível cadastrar uma matricula/Siape já cadastrada, por favor informe outra matricula/Siape';
                                $dados['erro']['class'] = 'alert-danger';
                                $usuario['matricula'] = null;
                            }
                        } else {
                            $dados['usuario_erro']['matricula']['msg'] = 'Informe a matricula/siape';
                            $dados['usuario_erro']['matricula']['class'] = 'has-error';
                        }
                        //email
                        if (!empty(filter_input(INPUT_POST, "nEmail", FILTER_SANITIZE_SPECIAL_CHARS))) {
                            $usuario['email'] = filter_input(INPUT_POST, "nEmail", FILTER_SANITIZE_SPECIAL_CHARS);
                            if ($admModel->read_specific('SELECT * FROM administrador WHERE email=:email', array('email' => $usuario['email']))) {
                                $dados['usuario_erro']['email']['msg'] = 'E-mail já cadastrado';
                                $dados['usuario_erro']['email']['class'] = 'has-error';
                                $dados['erro']['msg'] = '<i class="fa fa-info-circle" aria-hidden="true"></i> Não é possível cadastrar um e-mail já cadastrado, por favor informe outro endereço de e-mail';
                                $dados['erro']['class'] = 'alert-danger';
                                $usuario['email'] = null;
                            }
                        } else {
                            $dados['usuario_erro']['email']['msg'] = 'Informe o e-mail';
                            $dados['usuario_erro']['email']['class'] = 'has-error';
                        }
                        //senha
                        $senha = filter_input(INPUT_POST, "nSenha", FILTER_SANITIZE_SPECIAL_CHARS);
                        $rsenha = filter_input(INPUT_POST, "nRepetirSenha", FILTER_SANITIZE_SPECIAL_CHARS);
                        if (!empty($senha) && !empty($rsenha)) {
                            //senha
                            if ($senha == $rsenha) {
                                $usuario['senha'] = $rsenha;
                            } else {
                                $dados['usuario_erro']['senha']['msg'] = "Os campos 'Senha' e 'Repetir Senha' não estão iguais! ";
                                $dados['usuario_erro']['senha']['class'] = 'has-error';
                            }
                        } else {
                            $dados['usuario_erro']['senha']['msg'] = "Os campos 'Senha' e 'Repetir Senha' devem ser preenchidos";
                            $dados['usuario_erro']['senha']['class'] = 'has-error';
                        }
                        //coordenacao
                        $usuario['cargo'] = filter_input(INPUT_POST, "nCoordenacao", FILTER_SANITIZE_SPECIAL_CHARS);
                        //sexo
                        $usuario['sexo'] = filter_input(INPUT_POST, "nSexo", FILTER_SANITIZE_SPECIAL_CHARS);

                        $usuario['status'] = !empty(filter_input(INPUT_POST, "nStatus", FILTER_SANITIZE_SPECIAL_CHARS)) ? filter_input(INPUT_POST, "nStatus", FILTER_SANITIZE_SPECIAL_CHARS) : 0;

                        //nivel de acesso
                        $usuario['nivel'] = 1;

                        //imagem
                        if (isset($_FILES['tImagem-1']) && $_FILES['tImagem-1']['error'] == 0) {
                            $usuario['imagem'] = $_FILES['tImagem-1'];
                        }
                        if (isset($dados['usuario_erro']) && !empty($dados['usuario_erro'])) {
                            $dados['erro']['msg'] = '<i class="fa fa-info-circle" aria-hidden="true"></i> Preencha todos os campos obrigatórios (*).';
                            $dados['erro']['class'] = 'alert-danger';
                            $dados['usuario'] = $usuario;
                        } else {
                            $admModel->create($usuario);
                            $dados['erro']['msg'] = '<i class="fa fa-check" aria-hidden="true"></i> Cadastro realizado com sucesso!';
                            $dados['erro']['class'] = 'alert-success';
                            $_POST = array();
                        }
                    }
                }
            }

            $this->loadTemplate($view, $dados);
        } else {
            $url = "Location: " . BASE_URL . "login";
            header($url);
        }
    }
}
