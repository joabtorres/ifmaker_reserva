<?php

/*
 * config.php  - Este arquivo contem informações referente a: Conexão com banco de dados e URL Pádrão
 */

require 'environment.php';
$config = array();
define("NAME_PROJECT", "Sirin - Sistema de Reserva de Equipamentos de Informática");
if (ENVIRONMENT == 'development') {
    //Raiz
    define("BASE_URL", "https://localhost/sirin/");
    //Nome do banco
    $config['dbname'] = 'bd_sirelai';
    //host
    $config['host'] = 'localhost';
    //usuario
    $config['dbuser'] = 'root';
    //senha
    $config['dbpass'] = '';
} else {
    //Raiz
    define("BASE_URL", "http://sirelai.joabtorres.com.br/");
    //Nome do banco
    $config['dbname'] = 'bd_sirelai';
    //host
    $config['host'] = 'localhost';
    //usuario
    $config['dbuser'] = 'root';
    //senha
    $config['dbpass'] = 'senha';
}
