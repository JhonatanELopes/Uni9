-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 25/11/2025 às 01:18
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bancouninove`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_adm`
--

CREATE TABLE `tbl_adm` (
  `ID_Adm` int(11) NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Senha` varchar(255) NOT NULL,
  `Gerente` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbl_adm`
--

INSERT INTO `tbl_adm` (`ID_Adm`, `Nome`, `Email`, `Senha`, `Gerente`) VALUES
(3, 'Administrador', 'adm@adm.com', '$2y$10$jjLNMlAdzFdHSaU1qnNDqu883v2Tgul/Ki/cFHeoJVm2i.UZ9Rnyq', 1),
(14, 'Administrador1', 'Administrador1@adm.com', '$2y$10$4UQnjrwNtyyDj7iViG0wF.knJ4cKT8Wo5S2dfuDrEkgJsrQeGQThG', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_usuario`
--

CREATE TABLE `tbl_usuario` (
  `ID_Usuario` int(11) NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Telefone` varchar(20) DEFAULT NULL,
  `CPF` varchar(14) NOT NULL,
  `DataNasc` date DEFAULT NULL,
  `Modalidade` varchar(50) DEFAULT NULL,
  `Curso` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbl_usuario`
--

INSERT INTO `tbl_usuario` (`ID_Usuario`, `Nome`, `Email`, `Telefone`, `CPF`, `DataNasc`, `Modalidade`, `Curso`) VALUES
(8, 'Teste', 'teste@teste.com', '11943587899', '12345678901', '2025-11-05', 'Presencial', 'Educacao Fisica Bacharelado');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tbl_adm`
--
ALTER TABLE `tbl_adm`
  ADD PRIMARY KEY (`ID_Adm`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Índices de tabela `tbl_usuario`
--
ALTER TABLE `tbl_usuario`
  ADD PRIMARY KEY (`ID_Usuario`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `CPF` (`CPF`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tbl_adm`
--
ALTER TABLE `tbl_adm`
  MODIFY `ID_Adm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `tbl_usuario`
--
ALTER TABLE `tbl_usuario`
  MODIFY `ID_Usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
