<?php

/**
 * A classe 'controller' é responsável por fazer o carregamento das views e verifica nível de usuário
 * 
 * @author Joab Torres <joabtorres1508@gmail.com>
 * @version 1.0
 * @copyright  (c) 2017, Joab Torres Alencar - Analista de Sistemas 
 * @access public
 * @package core
 * @example classe controller
 */
class controller extends helper
{

    /**
     * Está função é responsável para carrega uma view;
     * @param String viewName - nome da view;
     * @param array $viewData - Dados para serem carregados na view
     * @access protected
     * @author Joab Torres <joabtorres1508@gmail.com>
     */
    public function loadView($viewName, $viewData = array()): void
    {
        extract($viewData);
        include 'views/' . $viewName . ".php";
    }

    /**
     * Está função é responsável para carrega um template estático, a onde ela chama chama uma função lo;
     * @param String viewName - nome da view;
     * @param array $viewData - Dados para serem carregados na view
     * @access protected
     * @author Joab Torres <joabtorres1508@gmail.com>
     */
    protected function loadTemplate($viewName, $viewData = array()): void
    {
        include 'views/template.php';
    }

    /**
     * Está função é responsável para carrega uma view dinamica dentro de um template estático
     * @param String viewName - nome da view;
     * @param array $viewData - Dados para serem carregados na view
     * @access protected
     * @author Joab Torres <joabtorres1508@gmail.com>
     */
    protected function loadViewInTemplate($viewName, $viewData = array()): void
    {
        extract($viewData);
        include 'views/' . $viewName . ".php";
    }

    /**
     * Está função é responsável para inicializar a sessa do usuário do sistema, seja ele usuario comum ou administrador
     * @param array $usuario - dados encontrados no banco de dados do usuário
     * @access protected
     * @author Joab Torres <joabtorres1508@gmail.com>
     */
    protected function setUserSession($usuario): void
    {
        //inicando sessao
        $_SESSION['usuario'] = array();
        //codigo
        $_SESSION['usuario']['id'] = $usuario['id'];
        //nome
        $_SESSION['usuario']['nome'] = $usuario['nome'];
        //sobrenome
        $_SESSION['usuario']['sobrenome'] = $usuario['sobrenome'];
        //matricula
        $_SESSION['usuario']['matricula'] = $usuario['matricula'];

        //cargo ~ somente para usuario
        if (isset($usuario['categoria']) && !empty($usuario['categoria'])) {
            $_SESSION['usuario']['categoria'] = $usuario['categoria'];
        } else {
            //categoria ~ somente para adiministrador
            $_SESSION['usuario']['categoria'] = $usuario['cargo'];
        }
        //img
        $_SESSION['usuario']['imagem'] = $usuario['imagem'];
        //nivel somente para administrador
        if (isset($usuario['nivel_acesso']) && !empty($usuario['nivel_acesso'])) {
            $_SESSION['usuario']['nivel'] = $usuario['nivel_acesso'];
        }
        //statu
        $_SESSION['usuario']['statu'] = $usuario['status'];
    }

    /**
     * verifica se o usuário está logado
     * @return bollean 
     * @access protected
     * @author Joab Torres <joabtorres1508@gmail.com>
     */
    protected function checkUser(): ?bool
    {
        if (isset($_SESSION['usuario']) && is_array($_SESSION['usuario']) && isset($_SESSION['usuario']['statu'])) {
            if ($_SESSION['usuario']['statu'] == 1) {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * verifica  o nível de acesso do usuário
     */
    protected function checkNivel(): bool|int
    {
        if (isset($_SESSION['usuario']) && is_array($_SESSION['usuario']) && isset($_SESSION['usuario']['statu']) && isset($_SESSION['usuario']['nivel'])) {
            if ($_SESSION['usuario']['statu'] == 1) {
                return $_SESSION['usuario']['nivel'];
            }
        } else {
            return false;
        }
    }

    /**
     * Esta função retorna o nome do usuario logado
     * @return string nome do usuario
     */
    protected function getNome(): string
    {
        if (isset($_SESSION['usuario']) && is_array($_SESSION['usuario']) && isset($_SESSION['usuario']['statu'])) {
            if ($_SESSION['usuario']['statu'] == 1) {
                return $_SESSION['usuario']['nome'];
            }
        } else {
            return "Usuario";
        }
    }

    /**
     * Esta função retorna o id do usuario logado
     * @return string id do usuario
     */
    protected function getId(): ?int
    {
        if (isset($_SESSION['usuario']) && is_array($_SESSION['usuario'])) {
            if ($_SESSION['usuario']['statu'] == 1) {
                return $_SESSION['usuario']['id'];
            }
        } else {
            return false;
        }
    }

    /**
     * Esta função retorna o id do usuario logado
     * @return string id do usuario
     */
    protected function getCategoria(): ?string
    {
        if (isset($_SESSION['usuario']) && is_array($_SESSION['usuario'])) {
            if ($_SESSION['usuario']['statu'] == 1) {
                return $_SESSION['usuario']['categoria'];
            }
        } else {
            return null;
        }
    }

    /**
     * Está função verifica  se o e-mail do usuário é valido, ou seja, se seu servido de email existe.
     * @param string $email
     * @return bollean 
     * @access protected
     * @author Joab Torres <joabtorres1508@gmail.com>
     */
    protected function validar_email($email): bool
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            list($usuario, $dominio) = explode("@", $email);
            if (checkdnsrr($dominio, 'MX')) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Está função verifica se o usuário está cadastrado no sistema, se ele estive será criado uma nova senha e enviado para o respectivo email
     *
     * @param string $email
     * @param string $modo
     * @return boolean
     */
    protected function recuperar(string $email, string $modo): bool
    {
        if ($this->checkUser() != 4) {
            if ($modo) {
                $usuarioModel = new administrador();
                $senha = $usuarioModel->newpassword($email);
            } else {
                $usuarioModel = new usuario();
                $senha = $usuarioModel->newpassword($email);
            }

            if ($senha) {
                // envia email ao usuário
                $assunto = NAME_PROJECT;
                $destinatario = $email;
                $mensagem = "<!DOCTYPE html>
                <html lang='pt-br'>
                <head>
                    <meta charset='UTF-8'>
                    <title>{$assunto}</title>
                </head>
                <body>
                    <div style='width: 98%;display: block;margin: 10px auto;padding: 0;font-family: sans-serif, Arial;border : 2px solid #357ca5;'>
                    <h3 style='background: #357ca5;color: white;padding: 10px;margin: 0;'>Nova Senha! <br/> <small>{$assunto} - Nova Senha</small></h3>
                        <p style='padding: 10px;line-height: 30px;'>
                                                Você solicitou uma nova senha de acesso ao <b>{$assunto}</b>, confira abaixo sua nova senha de acesso: <br/>
                                                <span style='font-weight:bold'>Email: </span><span style='color: #357ca5;'>{$email}</span><br/>
                                                <span style='font-weight:bold'>Nova Senha: </span> <span style='color: #357ca5;'>{$senha}</span><br/>
                                                     <a href='" . BASE_URL . "' style='text-decoration: none;'>Carregar Página</a>
                        </p>
                    </div>
                </body>
                </html>";
                $assunto .= " - NOVA SENHA";
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";
                $headers .= 'From: ' . $assunto . ' <joabtorres.develop@gmail.com>' . "\r\n";
                $headers .= 'X-Mailer: PHP/' . phpversion();
                mail($destinatario, $assunto, $mensagem, $headers);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Está função pertence a uma action do controle MVC, ela é responsável desloga o usuário do sistema, limpando a $_SESSION
     * @return void
     */
    public function logout(): void
    {
        if (isset($_SESSION)) {
            $_SESSION = array();
            header('location: ' . BASE_URL . 'home');
        }
    }
}
