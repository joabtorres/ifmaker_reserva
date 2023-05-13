<?php

/**
 * A classe 'excluirrController' é responsável para fazer o gerenciamento na exclusão  de usuários, adminstrador, equipamento, horario, reserva
 * @author Joab Torres <joabtorres1508@gmail.com>
 * @version 1.0
 * @copyright  (c) 2023, Joab Torres Alencar - Analista de Sistemas 
 * @access public
 * @package controllers
 * @example classe excluirController
 */
class excluirController extends controller
{


    public function index(string $cod): void
    {
        $this->reserva($cod);
    }

    /**
     * VIEW PARA EXCLUIR RESERVA
     *
     * @param string $cod md5
     * @return void
     */
    public function reserva(string $cod): void
    {
        if ($this->checkUser() && !empty($cod)) {
            $crudModel = new crud_db();
            if ($this->checkNivel()) {
                $crudModel->remove("DELETE FROM reserva WHERE md5(id)=:cod", array('cod' => $cod));
            } else {
                $crudModel->remove("DELETE FROM reserva WHERE md5(id)=:cod AND id_usuario=:id AND status=0", array('cod' => $cod, 'id' => $this->getId()));
            }
            $url = "Location: " . BASE_URL . "relatorio/reserva";
            header($url);
        } else {
            $url = "Location: " . BASE_URL . "home";
            header($url);
        }
    }

    /**
     * VIEW PARA EXCLUIR HORARIO
     *
     * @param string $cod md5
     * @return void
     */
    public function horario(string $cod): void
    {
        if ($this->checkUser() && !empty($cod) && $this->checkNivel()) {
            $crudModel = new crud_db();
            if ($crudModel->remove("DELETE FROM horario WHERE md5(id)=:cod", array('cod' => $cod))) {
                $url = "Location: " . BASE_URL . "relatorio/horario/1";
                header($url);
            } else {
                $url = "Location: " . BASE_URL . "relatorio/horario";
                header($url);
            }
        } else {
            $url = "Location: " . BASE_URL . "home";
            header($url);
        }
    }

    /**
     * VIEW PARA EXCLUIR EQUIPAMENTO
     *
     * @param string $cod
     * @return void
     */
    public function equipamento(string $cod): void
    {
        if ($this->checkUser() && !empty($cod) && $this->checkNivel()) {
            $crudModel = new crud_db();
            $resultado = $crudModel->read_specific("SELECT * FROM equipamento WHERE md5(id)= :id", array('id' => $cod));
            if ($resultado) {
                $crudModel->remove("DELETE FROM horario WHERE id_equipamento=:id ", array('id' => $resultado['id']));
                $crudModel->remove("DELETE FROM reserva WHERE id_equipamento=:id ", array('id' => $resultado['id']));
                $crudModel->remove("DELETE FROM equipamento WHERE id=:id ", array('id' => $resultado['id']));
            }
            $url = "Location: " . BASE_URL . "relatorio/equipamento/1";
            header($url);
        } else {
            $url = "Location: " . BASE_URL . "home";
            header($url);
        }
    }



    /**
     * VIEW PARA EXCLUIR USUARIO
     *
     * @param string $cod md5
     * @return void
     */
    public function usuario(string $cod): void
    {
        if ($this->checkUser() && !empty($cod) && $this->checkNivel()) {
            $userModel = new usuario();
            $crudModel = new crud_db();
            $resultado = $crudModel->read_specific("SELECT * FROM usuario WHERE md5(id)=:id", array('id' => $cod));
            if ($resultado) {
                $crudModel->remove("DELETE FROM reserva WHERE md5(id_usuario)=:id", array('id' => $cod));
                if ($userModel->remove(array('id' => $cod))) {
                    if (md5($this->getId()) == $cod) {
                        $url = "Location: " . BASE_URL . "login";
                        header($url);
                    } else {
                        $url = "Location: " . BASE_URL . "relatorio/administrador/1";
                        header($url);
                    }
                } else {
                    $url = "Location: " . BASE_URL . "404";
                    header($url);
                }
            }
        } else {
            $url = "Location: " . BASE_URL . "home";
            header($url);
        }
    }
    /**
     * view para excluir Administrador
     *
     * @param string $cod MD5
     * @return void
     */
    public function administrador(string $cod): void
    {
        if ($this->checkUser() && !empty($cod) && $this->checkNivel()) {
            $admModel = new administrador();
            if ($admModel->remove(array('cod' => $cod))) {
                if (md5($this->getId()) == $cod) {
                    $url = "Location: " . BASE_URL . "login";
                    header($url);
                } else {
                    $url = "Location: " . BASE_URL . "relatorio/administrador/1";
                    header($url);
                }
            } else {
                $url = "Location: " . BASE_URL . "404";
                header($url);
            }
        } else {
            $url = "Location: " . BASE_URL . "home";
            header($url);
        }
    }
}
