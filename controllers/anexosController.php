<?php

/**
 * A classe 'anexosController' é responsável para fazer o carregamento da página anexo do sistema
 * 
 * @author Joab Torres <joabtorres1508@gmail.com>
 * @version 1.0
 * @copyright  (c) 2023, Joab Torres Alencar - Analista de Sistemas 
 * @access public
 * @package controllers
 * @example classe anexosController
 */
class anexosController extends controller
{

	public function index()
	{
		$viewName = 'anexos';
		$this->loadTemplate($viewName);
	}
}
