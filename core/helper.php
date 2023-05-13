<?php

/**
 * A classe 'helper' é responsável para fazer o tratamento e formatações 
 * 
 * @author Joab Torres <joabtorres1508@gmail.com>
 * @version 1.0
 * @copyright  (c) 2023, Joab Torres Alencar - Analista de Sistemas 
 * @access public
 * @package core
 * @example classe helper
 */
class helper
{

    /**
     * Está função é responsável para converte uma data do padrão 'dia/mes/ano' para 'ano-mes-dia'
     * @param string $date - data solicitada pelo parametro
     * r
     * @access protected
     * @return null|string $date - data formatada no padrão banco MySQL
     * @author Joab Torres <joabtorres1508@gmail.com>
     */
    protected function formatDateBD(string $date): ?string
    {
        $arrayDate = explode("/", $date);
        if (count($arrayDate) == 3) {
            return $arrayDate[2] . '-' . $arrayDate[1] . '-' . $arrayDate[0];
        } else {
            return null;
        }
    }

    /**
     * Está função é responsável para converte uma data do padrão 'ano-mes-dia' para 'dia/mes/ano'
     * @param string $date - data solicitada pelo parametro
     * @access protected
     * @return null|string $date - data formatada no padrão brasileiro
     * @author Joab Torres <joabtorres1508@gmail.com>
     */
    protected function formatDateView(string $date): ?string
    {
        $arrayDate = explode("-", $date);
        if (count($arrayDate) == 3) {
            return $arrayDate[2] . '/' . $arrayDate[1] . '/' . $arrayDate[0];
        } else {
            return null;
        }
    }

    /**
     * Está função é responsável para converte uma data do padrão 'ano-mes-dia' para 'dia de mes de ano'
     * @param string  $date - data solicitada pelo parametro
     * r
     * @access protected
     * @return null|string $resultado - retorna a data dia de mês de ano 15 de agosto de 2019
     * @author Joab Torres <joabtorres1508@gmail.com>
     */
    protected function formatDateViewComplete(string $date): ?string
    {
        $arrayDate = explode("-", $date);
        if (count($arrayDate) == 3) {
            return $arrayDate[2] . ' de ' . $this->getMes($arrayDate[1]) . ' de ' . $arrayDate[0];
        } else {
            return null;
        }
    }

    /**
     * Está função é responsável para formata o time 00:00:00 em 00:00
     * @param string $time = $time selecionado
     * @access protected
     * @return null|string $resultado -  retorna os valores  00:00 ou false
     * @author Joab Torres <joabtorres1508@gmail.com>
     */
    protected function formatTimeView(string $time): ?string
    {
        $arrayTime = explode(":", $time);
        if (count($arrayTime) == 3) {
            return $arrayTime[0] . ':' . $arrayTime[1];
        } else {
            return null;
        }
    }

    /**
     * Está função é responsável para retorna o nome do més
     * @param string $mes = mês selecionado
     * @access protected
     * @return null|string $resultado
     * @author Joab Torres <joabtorres1508@gmail.com>
     */
    protected function getMes(string $mes): ?string
    {
        $array = array('janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setemmbro', 'outubro', 'novembro', 'dezembro');
        $resultado = null;
        for ($i = 0; $i < count($array); $i++) {
            if (($i + 1) == $mes) {
                $resultado = $array[$i];
            }
        }
        return $resultado;
    }

    /**
     * Está função é responsável retorna o dia e data da semana
     * @access protected
     * @return array $data_semana - array com indice 'dia' e 'data' da semana
     * @author Joab Torres <joabtorres1508@gmail.com>
     */
    protected function getDataSemana(): array
    {
        $data_hoje = time();
        $d = date("d", $data_hoje);
        $mes = date("m", $data_hoje);
        $ano = date("Y", $data_hoje);
        $dia_semana = date("w", $data_hoje);
        $proximo = 7 - $dia_semana;
        $anterior = 7 - $proximo;
        $cont = 0;
        $cont2 = $anterior;
        $nome_dias = array("1" => "Segunda-feira", "2" => "Terça-feira", "3" => "Quarta-feira", "4" => "Quinta-feira", "5" => "Sexta-feira", "6" => "Sábado");
        $data_semana = array();
        for ($i = 0; $i < 7; $i++) {
            if ($i < $anterior) {
                $dia = mktime(0, 0, 0, $mes, $d - $cont2, $ano);
                $dias[$i] = date("d/m/Y", $dia);
                if ($i > 0 && $i < 6) {
                    if (date("d", $dia) == $d) {
                        $data_semana[$i]['dia'] = $nome_dias[$i];
                        $data_semana[$i]['data'] = date("Y-m-d", $dia);
                    } else {
                        $data_semana[$i]['dia'] = $nome_dias[$i];
                        $data_semana[$i]['data'] = date("Y-m-d", $dia);
                    }
                }
                $cont2--;
            } else {
                $dia = mktime(0, 0, 0, $mes, $d + $cont, $ano);
                $dias[$i] = date("d/m/Y", $dia);
                if ($i > 0 && $i < 6) {
                    if (date("d", $dia) == $d) {
                        $data_semana[$i]['dia'] = $nome_dias[$i];
                        $data_semana[$i]['data'] = date("Y-m-d", $dia);
                    } else {
                        $data_semana[$i]['dia'] = $nome_dias[$i];
                        $data_semana[$i]['data'] = date("Y-m-d", $dia);
                    }
                }
                $cont++;
            }
            if ($cont == $proximo) {
                break;
            }
        }
        return $data_semana;
    }

    /**
     * Está função é responsável retorna uma cor especifica para cada categoria de usuario que solicita uma reserva.
     * @param string $categoria = categoria do usuario
     * @access protected
     * @return string $resultado - retorna uma classe html para mudar a cor do texto
     * @author Joab Torres <joabtorres1508@gmail.com>
     */
    protected function getCategoriaReserva(string $categoria): string
    {
        switch ($categoria) {
            case 'Aluno(a)':
                $resultado = 'text-info';
                break;
            case 'Professor(a)':
                $resultado = 'text-success';
                break;
            case 'Secretaria':
                $resultado = 'text-danger';
                break;
            case 'Demanda Externa':
                $resultado = 'text-warning';
                break;
        }
        return $resultado;
    }
    /**
     * Função para verificar e retornar a quantidade de dias uteis
     *
     * @param integer $dias total de dias
     * @return integer qtd de dias utes
     */
    public function getDiasUteis(int $dias): int
    {
        if ($dias > 0) {
            $qtd = 0;
            $qtdDias = 1;
            $d = date('Y-m-d');
            $d = strtotime($d);
            while ($qtdDias <= $dias) {
                $d = strtotime('+1 day', $d);
                if (($this->getDiaSemana($d) == 'sabado') || ($this->getDiaSemana($d) == 'domingo')) {
                } else {
                    $qtdDias++;
                }
                $qtd++;
            }
            return $qtd;
        } else {
            echo 0;
        }
    }

    /**
     * Está função é responsável o nome do dia da semana
     * @param string $data = data especificada
     * @access protected
     * @return string $diasemana[$diasemana_numero] -  retorna os valores  'domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta' ou 'sabado'
     */
    protected function getDiaSemana($data): string
    {
        $diasemana = array('domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado');
        $diasemana_numero = date('w', $data);
        return $diasemana[$diasemana_numero];
    }
}
