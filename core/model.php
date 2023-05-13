<?php

/**
 * A classe 'model' é responsável para inicializa a instância na classe PDO e armazena na variavel $db;
 * 
 * @author Joab Torres <joabtorres1508@gmail.com>
 * @version 1.0
 * @copyright  (c) 2019 Joab Torres Alencar - Analista de Sistemas 
 * @access public
 * @package core
 * @example classe model
 */
class model
{

    /**
     * Instância do objeto da classe PDO()
     * @access protected
     * @var PDO
     * @author Joab Torres <joabtorres1508@gmail.com>
     */
    protected PDO $db;

    /**
     * Está função é inicializada ao solicita uma instáncia das classes models, fazendo com que armazene a conexão do banco de dados via classe PDO na variavel $db;
     * @access public
     * @author Joab Torres <joabtorres1508@gmail.com>
     */
    public function __construct()
    {
        global $config;
        try {
            $this->db = new PDO('mysql:dbname=' . $config['dbname'] . ';host=' . $config['host'] . ';charset=utf8', $config['dbuser'], $config['dbpass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Conexão ao banco de dados falhou: " . $e->getMessage();
        }
    }
}
