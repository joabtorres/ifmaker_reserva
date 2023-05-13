<?php

/**
 * A classe 'loginController' é responsável por fazer validação de login para que tenha acesso ao sistema, podendo verifica se o e-mail e valido e exibindo a opção de recupera senha, 
 * 
 * @author Joab Torres <joabtorres1508@gmail.com>
 * @version 1.0
 * @copyright  (c) 2017, Joab Torres Alencar - Analista de Sistemas 
 * @access public
 * @package controllers
 * @example classe loginController
 */
class loginController extends controller
{

    /**
     * VIEW DE LOGIN
     *
     * @return void
     */
    public function index(): void
    {
        $view = "login";
        $dados = array();
        $_SESSION = array();
        if (isset($_POST['nEntrar']) && !empty($_POST['nEntrar'])) {
            //recaptcha validando
            $email = filter_input(INPUT_POST, "nSerachUsuario", FILTER_SANITIZE_SPECIAL_CHARS);
            $senha = filter_input(INPUT_POST, "nSearchSenha", FILTER_SANITIZE_SPECIAL_CHARS);
            if (!empty($email) && !empty($senha)) {
                $usuario = array('usuario' => $email, 'senha' => md5(sha1($senha)));
                $modoAdmin = !empty($_POST['nModo']) && $_POST["nModo"] == "adm" ? "adm" : null;
                if (!empty($modoAdmin)) {
                    $adminModel = new administrador();
                    $resultado = $adminModel->read_specific('SELECT * FROM administrador WHERE email=:usuario AND senha=:senha', $usuario);
                    if (!$resultado) {
                        $dados['erro']['msg'] = '<i class="fa fa-info-circle" aria-hidden="true"></i> O Campo Usuário ou Senha está incorreto!';
                    }
                } else {
                    $usuarioModel = new usuario();
                    $resultado = $usuarioModel->read_specific('SELECT * FROM usuario WHERE email=:usuario AND senha=:senha', $usuario);
                    if (!$resultado) {
                        $dados['erro']['msg'] = '<i class="fa fa-info-circle" aria-hidden="true"></i> O Campo Usuário ou Senha está incorreto!';
                    }
                }
                if (!empty($resultado) && empty($resultado['status'])) {
                    $dados['erro']['msg'] = '<i class="fa fa-info-circle" aria-hidden="true"></i> O Acesso deste usuário está <b>DESABILITADO</b>!';
                }
                if (!isset($dados['erro']) && empty($dados['erro'])) {
                    $this->setUserSession($resultado);
                    $url = "Location:" . BASE_URL . "home";
                    header($url);
                }
            } else {
                $dados['erro']['msg'] = '<i class="fa fa-info-circle" aria-hidden="true"></i> O Campo Usuário ou Senha não está preenchido!';
            }
        }


        $this->loadView($view, $dados);

        //criando nova senha
        if (isset($_POST['nEnviar'])) {
            $email = addslashes(trim($_POST['nEmail']));
            $modo = !empty($_POST['nModo2']) ? 'admin' : null;
            $_POST = null;
            if ($this->validar_email($email) && $this->recuperar($email, $modo)) {
                echo '<script>$("#modal_confirmacao_email").modal();</script>';
            } else {
                echo '<script>$("#modal_invalido_email").modal();</script>';
            }
        }
    }
}
