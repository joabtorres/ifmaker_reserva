-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 13-Maio-2023 às 20:08
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Estrutura da tabela `administrador`
--

CREATE TABLE `administrador` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `sobrenome` varchar(100) NOT NULL,
  `matricula` varchar(45) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(32) NOT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `sexo` varchar(10) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `nivel_acesso` int(1) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `administrador`
--

INSERT INTO `administrador` (`id`, `nome`, `sobrenome`, `matricula`, `email`, `senha`, `cargo`, `sexo`, `imagem`, `nivel_acesso`, `status`) VALUES
(1, 'Joab', 'Torres', '2015790058', 'joabtorres1508@gmail.com', '6116afedcb0bc31083935c1c262ff4c9', 'Coordernação de Curso de Tecnologia em Análise e Desenvolvimento de Sistemas (CTADS)', 'M', 'uploads/administradores/user_masculino.png', 1, 1),
(4, 'Ricardo', 'Guimarães', '44545465', 'ricardo@gmail.com', '47cafbff7d1c4463bbe7ba972a2b56e3', 'Coordernação de Curso Técnico em Informática (CTI)', 'F', 'uploads/administradores/cab82f1510cf46dcf68c8983f7c6d59f.jpg', 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `dias_uteis`
--

CREATE TABLE `dias_uteis` (
  `id` int(11) UNSIGNED NOT NULL,
  `categoria` varchar(20) DEFAULT NULL,
  `minimo` int(2) DEFAULT NULL,
  `maximo` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `dias_uteis`
--

INSERT INTO `dias_uteis` (`id`, `categoria`, `minimo`, `maximo`) VALUES
(1, 'Aluno(a)', 2, 4),
(2, 'Professor(a)', 3, 7),
(3, 'Secretaria', 3, 7),
(4, 'Demanda Externa', 3, 7);

-- --------------------------------------------------------

--
-- Estrutura da tabela `equipamento`
--

CREATE TABLE `equipamento` (
  `id` int(11) NOT NULL,
  `nome` varchar(45) DEFAULT NULL,
  `qtd` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `equipamento`
--

INSERT INTO `equipamento` (`id`, `nome`, `qtd`, `status`) VALUES
(7, 'Computador', 10, 1),
(8, 'PRODIUTO', 123, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `horario`
--

CREATE TABLE `horario` (
  `id` int(11) NOT NULL,
  `id_equipamento` int(11) NOT NULL,
  `hora_inicial` time NOT NULL,
  `hora_final` time NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `horario`
--

INSERT INTO `horario` (`id`, `id_equipamento`, `hora_inicial`, `hora_final`, `status`) VALUES
(18, 8, '07:44:00', '08:44:00', 1),
(17, 7, '11:11:00', '18:00:00', 1),
(16, 7, '08:10:00', '16:00:00', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `reserva`
--

CREATE TABLE `reserva` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_equipamento` int(11) NOT NULL,
  `data_inicial` date NOT NULL,
  `data_final` date NOT NULL,
  `horario_inicial` time NOT NULL,
  `horario_final` time NOT NULL,
  `turma` varchar(255) NOT NULL,
  `disciplina` varchar(255) NOT NULL,
  `segunda` int(1) NOT NULL,
  `terca` int(1) NOT NULL,
  `quarta` int(1) NOT NULL,
  `quinta` int(1) NOT NULL,
  `sexta` int(1) NOT NULL,
  `sabado` int(1) NOT NULL,
  `status` int(1) DEFAULT NULL,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `reserva`
--

INSERT INTO `reserva` (`id`, `id_usuario`, `id_equipamento`, `data_inicial`, `data_final`, `horario_inicial`, `horario_final`, `turma`, `disciplina`, `segunda`, `terca`, `quarta`, `quinta`, `sexta`, `sabado`, `status`, `descricao`) VALUES
(136, 1, 7, '2023-05-15', '2023-05-23', '08:10:00', '16:00:00', '', '', 1, 1, 0, 0, 0, 0, 1, NULL),
(137, 1, 7, '2023-05-16', '2023-05-19', '08:10:00', '16:00:00', '', '', 1, 1, 1, 1, 0, 0, 1, 'Aprovado');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(20) NOT NULL,
  `sobrenome` varchar(100) NOT NULL,
  `matricula` varchar(30) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(32) NOT NULL,
  `cpf` varchar(15) NOT NULL,
  `nascimento` date DEFAULT NULL,
  `categoria` varchar(15) NOT NULL,
  `curso` varchar(255) DEFAULT NULL,
  `sexo` varchar(10) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `status` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `sobrenome`, `matricula`, `email`, `senha`, `cpf`, `nascimento`, `categoria`, `curso`, `sexo`, `imagem`, `status`) VALUES
(1, 'Joab', 'Torres Alencar', '2015790058', 'joabtorres1508@gmail.com', '6116afedcb0bc31083935c1c262ff4c9', '000.378.402-90', '1993-08-15', 'Aluno(a)', 'PÓS-GRADUAÇÃO EM EDUCAÇÃO DO CAMPO E DESENVOLVIMENTO SUSTENTÁVEL NA AMAZÔNIA', 'M', 'uploads/usuarios/user_masculino.png', 1),
(6, 'Rafael', 'Silva', '15456464', 'rafael@gmail.com', '6116afedcb0bc31083935c1c262ff4c9', '111.111.111-11', '1993-08-15', 'Demanda Externa', NULL, 'M', 'uploads/usuarios/7c510a3e9475519aa5af5a318291a95d.jpg', 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matricula_UNIQUE` (`matricula`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- Índices para tabela `dias_uteis`
--
ALTER TABLE `dias_uteis`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `equipamento`
--
ALTER TABLE `equipamento`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `horario`
--
ALTER TABLE `horario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_idLaboratorio` (`id_equipamento`);

--
-- Índices para tabela `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reserva_usuario_idx` (`id_usuario`),
  ADD KEY `fk_reserva_laboratorio1_idx` (`id_equipamento`);

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD UNIQUE KEY `matricula_UNIQUE` (`matricula`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `administrador`
--
ALTER TABLE `administrador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `dias_uteis`
--
ALTER TABLE `dias_uteis`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `equipamento`
--
ALTER TABLE `equipamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `horario`
--
ALTER TABLE `horario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `reserva`
--
ALTER TABLE `reserva`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `fk_reserva_laboratorio1` FOREIGN KEY (`id_equipamento`) REFERENCES `equipamento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_reserva_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
