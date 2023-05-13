<?php

/**
 * A classe 'crud_db' é responsável para efetiva comandos sql no banco de dados, como, insert, update, select, delete, count;
 * 
 * @author Joab Torres <joabtorres1508@gmail.com>
 * @version 1.0
 * @copyright  (c) 2019, Joab Torres Alencar - Analista de Sistemas 
 * @access public
 * @package models
 * @example classe crud_db
 */
class crud_db extends model
{

    /**
     * String $numRows - referente q quantidade de linhas obtidas no select;
     * @access private
     * @var int
     * @author Joab Torres <joabtorres1508@gmail.com>
     */
    private int $numRows;

    /**
     * Está função tem como objetivo retorna a quantidade de registro encontrados armazenados na variavel $numRows
     * @access public
     * @return int
     * @author Joab Torres <joabtorres1508@gmail.com>
     */
    public function getNumRows(): int
    {
        return $this->numRows;
    }

    /**
     * Está função é responsável para cadastrar novos registros;
     * @param string $sql_command  - Comando SQL;
     * @param array $data - Dados salvo em array para seres setados por um foreach;
     * @access public
     * @return boolean 
     * @author Joab Torres <joabtorres1508@gmail.com>
     */
    public function create(string $sql_command, array $data): ?bool
    {
        try {
            $sql = $this->db->prepare($sql_command);
            foreach ($data as $indice => $valor) {
                $sql->bindValue(":" . $indice, $valor);
            }
            $sql->execute();
            return true;
        } catch (PDOException $ex) {
            echo '<script> alert("Mensagem: ' . $ex->getMessage() . '")</script>';
        }
    }

    /**
     * Está função é responsável para consultas no banco e retorna os resultados obtidos;
     * @param string $sql_command  - Comando SQL;
     * @param array $data - Dados salvo em array para seres setados por um foreach;
     * @access public
     * @return array $sql->fetchAll() [caso encontre] | bollean FALSE [caso contrário] 
     * @author Joab Torres <joabtorres1508@gmail.com>
     */
    public function read(string $sql_command, array $data = array()): bool|array
    {
        try {
            if (!empty($data)) {
                $sql = $this->db->prepare($sql_command);
                foreach ($data as $indice => $valor) {
                    $sql->bindValue(":" . $indice, $valor);
                }
                $sql->execute();
            } else {
                $sql = $this->db->query($sql_command, PDO::FETCH_ASSOC);
            }

            if ($sql->rowCount() > 0) {
                $this->numRows = $sql->rowCount();
                return $sql->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $this->numRows = 0;
                return false;
            }
        } catch (PDOException $ex) {
            echo '<script> alert("Mensagem: ' . $ex->getMessage() . '")</script>';
            return false;
        }
    }

    /**
     * Está função é responsável para consultas no banco e retorna os resultados obtidos;
     * @param string $sql_command  - Comando SQL;
     * @param array $data - Dados salvo em array para seres setados por um foreach;
     * @access public
     * @return array $sql->fetch() [caso encontre] | bollean FALSE [caso contrário] 
     * @author Joab Torres <joabtorres1508@gmail.com>
     */
    public function read_specific(string $sql_command, array $data = array()): bool|array
    {
        try {
            if (!empty($data)) {
                $sql = $this->db->prepare($sql_command);

                foreach ($data as $indice => $valor) {
                    $sql->bindValue(":" . $indice, $valor);
                }
                $sql->execute();
            } else {
                $sql = $this->db->query($sql_command, PDO::FETCH_ASSOC);
            }
            if ($sql->rowCount() > 0) {
                $this->numRows = $sql->rowCount();
                return $sql->fetch(PDO::FETCH_ASSOC);
            } else {
                $this->numRows = 0;
                return FALSE;
            }
        } catch (PDOException $ex) {
            echo '<script> alert("Mensagem: ' . $ex->getMessage() . '")</script>';
            return false;
        }
    }

    /**
     * Está função é responsável para altera um registro específico;
     * @param String $sql_command  - Comando SQL;
     * @param Array $data - Dados salvo em array para seres setados por um foreach;
     * @access public
     * @return bollean TRUE ou FALSE
     * @author Joab Torres <joabtorres1508@gmail.com>
     */
    public function update(string $sql_command, array $data): bool
    {
        try {
            $sql = $this->db->prepare($sql_command);
            foreach ($data as $indice => $valor) {
                $sql->bindValue(":" . $indice, $valor);
            }
            $sql->execute();
            return true;
        } catch (PDOException $ex) {
            echo '<script> alert("Mensagem: ' . $ex->getMessage() . '")</script>';
            return false;
        }
    }

    /**
     * Está é responsável excluir um registro específico
     * @param string $sql_command  - Comando SQL;
     * @param array $data - Dados salvo em array para seres setados por um foreach;
     * @access public
     * @return boolean TRUE or FALSE
     * @author Joab Torres <joabtorres1508@gmail.com>
     */
    public function remove(string $sql_command, array $data): bool
    {
        try {
            $sql = $this->db->prepare($sql_command);
            foreach ($data as $indice => $valor) {
                $sql->bindValue(":" . $indice, $valor);
            }
            $sql->execute();
            return true;
        } catch (PDOException $ex) {
            echo '<script> alert("Mensagem: ' . $ex->getMessage() . '")</script>';
            return false;
        }
    }
}
