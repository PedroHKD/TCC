-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 03/12/2024 às 02:20
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
-- Banco de dados: `tcc`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `campeoes`
--

CREATE TABLE `campeoes` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `campeoes`
--

INSERT INTO `campeoes` (`id`, `nome`) VALUES
(1, 'Annie'),
(2, 'Olaf'),
(3, 'Galio'),
(4, 'Twisted Fate'),
(5, 'Xin Zhao'),
(6, 'Urgot'),
(7, 'LeBlanc'),
(8, 'Vladimir'),
(9, 'Fiddlesticks'),
(10, 'Kayle'),
(11, 'Master Yi'),
(12, 'Alistar'),
(13, 'Ryze'),
(14, 'Sion'),
(15, 'Sivir'),
(16, 'Soraka'),
(17, 'Teemo'),
(18, 'Tristana'),
(19, 'Warwick'),
(20, 'Nunu & Willump'),
(21, 'Miss Fortune'),
(22, 'Ashe'),
(23, 'Tryndamere'),
(24, 'Jax'),
(25, 'Morgana'),
(26, 'Zilean'),
(27, 'Singed'),
(28, 'Evelynn'),
(29, 'Twitch'),
(30, 'Karthus'),
(31, 'Cho Gath'),
(32, 'Amumu'),
(33, 'Rammus'),
(34, 'Anivia'),
(35, 'Shaco'),
(36, 'Dr. Mundo'),
(37, 'Sona'),
(38, 'Kassadin'),
(39, 'Irelia'),
(40, 'Janna'),
(41, 'Gangplank'),
(42, 'Corki'),
(43, 'Karma'),
(44, 'Taric'),
(45, 'Veigar'),
(48, 'Trundle'),
(50, 'Swain'),
(51, 'Caitlyn'),
(53, 'Blitzcrank'),
(54, 'Malphite'),
(55, 'Katarina'),
(56, 'Nocturne'),
(57, 'Maokai'),
(58, 'Renekton'),
(59, 'Jarvan IV'),
(60, 'Elise'),
(61, 'Orianna'),
(62, 'Wukong'),
(63, 'Brand'),
(64, 'Lee Sin'),
(67, 'Vayne'),
(68, 'Rumble'),
(69, 'Cassiopeia'),
(72, 'Skarner'),
(74, 'Heimerdinger'),
(75, 'Nasus'),
(76, 'Nidalee'),
(77, 'Udyr'),
(78, 'Poppy'),
(79, 'Gragas'),
(80, 'Pantheon'),
(81, 'Ezreal'),
(82, 'Mordekaiser'),
(83, 'Yorick'),
(84, 'Akali'),
(85, 'Kennen'),
(86, 'Garen'),
(89, 'Leona'),
(90, 'Malzahar'),
(98, 'Shen'),
(99, 'Lux'),
(101, 'Xerath'),
(102, 'Shyvana'),
(103, 'Ahri'),
(104, 'Graves'),
(105, 'Fizz'),
(106, 'Volibear'),
(107, 'Rengar'),
(110, 'Varus'),
(111, 'Nautilus'),
(112, 'Viktor'),
(113, 'Sejuani'),
(114, 'Fiora'),
(115, 'Ziggs'),
(117, 'Lulu'),
(119, 'Draven'),
(120, 'Hecarim'),
(121, 'Kha Zix'),
(122, 'Darius'),
(126, 'Jayce'),
(127, 'Lissandra'),
(131, 'Diana'),
(133, 'Quinn'),
(134, 'Syndra'),
(136, 'Aurelion Sol'),
(141, 'Kayn'),
(142, 'Zoe'),
(143, 'Zyra'),
(145, 'Kai Sa'),
(147, 'Seraphine'),
(150, 'Gnar'),
(154, 'Zac'),
(157, 'Yasuo'),
(161, 'Vel Koz'),
(163, 'Taliyah'),
(166, 'Akshan'),
(201, 'Braum'),
(202, 'Jhin'),
(203, 'Kindred'),
(222, 'Jinx'),
(223, 'Tahm Kench'),
(234, 'Viego'),
(235, 'Senna'),
(236, 'Lucian'),
(238, 'Zed'),
(245, 'Ekko'),
(246, 'Qiyana'),
(254, 'Vi'),
(266, 'Aatrox'),
(268, 'Azir'),
(350, 'Yuumi'),
(360, 'Samira'),
(412, 'Thresh'),
(420, 'Illaoi'),
(421, 'Rek Sai'),
(427, 'Ivern'),
(432, 'Bard'),
(497, 'Rakan'),
(498, 'Xayah'),
(516, 'Ornn'),
(517, 'Sylas'),
(518, 'Neeko'),
(523, 'Aphelios'),
(526, 'Rell'),
(555, 'Pyke'),
(711, 'Vex'),
(777, 'Yone'),
(875, 'Sett'),
(876, 'Lillia'),
(887, 'Gwen'),
(888, 'Renata Glasc'),
(895, 'Nilah'),
(897, 'K Sante'),
(901, 'Smolder'),
(902, 'Milio'),
(910, 'Hwei'),
(950, 'Naafiri'),
(267, 'Nami');

-- --------------------------------------------------------

--
-- Estrutura para tabela `campeonatos`
--

CREATE TABLE `campeonatos` (
  `cd_campeonato` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `data_inicio` datetime NOT NULL,
  `numero_equipes` int(11) NOT NULL CHECK (`numero_equipes` in (4,8)),
  `status` enum('ABERTO','EM_ANDAMENTO','FINALIZADO') DEFAULT 'ABERTO',
  `equipes_inscritas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`equipes_inscritas`)),
  `chaveamento` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`chaveamento`)),
  `balanceamento_elo` tinyint(1) DEFAULT 1,
  `organizador` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `campeonatos`
--

INSERT INTO `campeonatos` (`cd_campeonato`, `nome`, `data_inicio`, `numero_equipes`, `status`, `equipes_inscritas`, `chaveamento`, `balanceamento_elo`, `organizador`, `created_at`) VALUES
(1, 'Torneio Iniciante', '2024-11-23 07:48:43', 4, 'ABERTO', '[{\"cd_equipe\":7,\"nome\":\"asdasdasd\",\"membros\":{\"jogador1\":{\"puuid\":\"ZZrsmoEaWHDp1Ug9YfVtaah8AOI_tGt9yUzHzc0ttO_-zPknJZhZciBTwwxC1BCENNb4IZk4ocQmBQ\",\"gameName\":\"FUR Wiz\",\"tagLine\":\"CBLOL\",\"tier\":\"CHALLENGER\"},\"jogador2\":{\"puuid\":\"nfffmrDXs30MOPVJsRCJ8Ets6E8Lw_gtJjEpJumsnHKORWps-SXKGIxUp5XG3naOHskLgazKBYdkOQ\",\"gameName\":\"VKS Kisee\",\"tagLine\":\"CBLOL\",\"tier\":\"CHALLENGER\"},\"jogador3\":{\"puuid\":\"IcATdGhgKv_FVQECYPdeWhE8G0ibkLoG0ZbsYmJJ-1BvpftLbuue7eGQcsIVEinv1bG-t48ntIYOEg\",\"gameName\":\"young fla\",\"tagLine\":\"furia\",\"tier\":\"CHALLENGER\"},\"jogador4\":{\"puuid\":\"FiOwAXEBBzdGTSGku_AEfhcZftWP4HsT8tQsqixTh4KUs6siWxdZS44LBYfHasn8CraCSBCHgIHzTw\",\"gameName\":\"frosty\",\"tagLine\":\"KR3\",\"tier\":\"CHALLENGER\"},\"jogador5\":{\"puuid\":\"52tfTOw0MHQM8mgCzhal4qBu9ZiEhPLSJxt41L3jPlc320uGSYZEt3AgNIU5M7yeUALIdC-m_BNuGw\",\"gameName\":\"randal\",\"tagLine\":\"ZERO1\",\"tier\":\"CHALLENGER\"}}}]', NULL, 1, 'Vextrion#1337', '2024-11-16 10:48:43'),
(5, 'Os bolinhas', '2024-11-21 00:58:00', 4, 'FINALIZADO', '[{\"cd_equipe\":7,\"nome\":\"asdasdasd\",\"membros\":{\"jogador1\":{\"puuid\":\"ZZrsmoEaWHDp1Ug9YfVtaah8AOI_tGt9yUzHzc0ttO_-zPknJZhZciBTwwxC1BCENNb4IZk4ocQmBQ\",\"gameName\":\"FUR Wiz\",\"tagLine\":\"CBLOL\",\"tier\":\"CHALLENGER\"},\"jogador2\":{\"puuid\":\"nfffmrDXs30MOPVJsRCJ8Ets6E8Lw_gtJjEpJumsnHKORWps-SXKGIxUp5XG3naOHskLgazKBYdkOQ\",\"gameName\":\"VKS Kisee\",\"tagLine\":\"CBLOL\",\"tier\":\"CHALLENGER\"},\"jogador3\":{\"puuid\":\"IcATdGhgKv_FVQECYPdeWhE8G0ibkLoG0ZbsYmJJ-1BvpftLbuue7eGQcsIVEinv1bG-t48ntIYOEg\",\"gameName\":\"young fla\",\"tagLine\":\"furia\",\"tier\":\"CHALLENGER\"},\"jogador4\":{\"puuid\":\"FiOwAXEBBzdGTSGku_AEfhcZftWP4HsT8tQsqixTh4KUs6siWxdZS44LBYfHasn8CraCSBCHgIHzTw\",\"gameName\":\"frosty\",\"tagLine\":\"KR3\",\"tier\":\"CHALLENGER\"},\"jogador5\":{\"puuid\":\"52tfTOw0MHQM8mgCzhal4qBu9ZiEhPLSJxt41L3jPlc320uGSYZEt3AgNIU5M7yeUALIdC-m_BNuGw\",\"gameName\":\"randal\",\"tagLine\":\"ZERO1\",\"tier\":\"CHALLENGER\"}}},{\"cd_equipe\":31,\"nome\":\"kajsnc\",\"membros\":{\"jogador1\":{\"puuid\":\"32hnIjs0nLJC7AFRZo7bnWDkBIfb01Glie1aCmU7fA7-UuaR06nwO7BCRitawjNXTYlwyzj2zQI5xA\",\"gameName\":\"J\\u00e4gerwolf\",\"tagLine\":\"6969\",\"tier\":\"DIAMOND\"},\"jogador2\":{\"puuid\":\"uZBozUzvrTD8FWlcWERB9R-vkm7PYiVMtNyoVK09rfksZydCw9uenNI5LPZKQob0SbUbbgZScj7XHw\",\"gameName\":\"Espi\\u00e3o\",\"tagLine\":\"CBLOL\",\"tier\":\"DIAMOND\"},\"jogador3\":{\"puuid\":\"t88FktB2p5r5AoE55TAsSQFfjAKkj1V3jkZK84-1d1yq3IULuLsirDjtkN3iSl5BRdOcc0MEne0V2w\",\"gameName\":\"SHADYZERA1\",\"tagLine\":\"666\",\"tier\":\"DIAMOND\"},\"jogador4\":{\"puuid\":\"trJ3P0eKasOH-24vuX5RrKh95xpqwlu979xb98pdIg4_ibsxWKN__AukMdQ5ecYsQexC7gHs6PZLEg\",\"gameName\":\"cOnd\",\"tagLine\":\"BR1\",\"tier\":\"DIAMOND\"},\"jogador5\":{\"puuid\":\"WiIPjf0J1VPwZIon0MxjI2Qz6J2JeuOnspg-hKDpNbgJ2wDI7ANEsh9XKXmkvcfEGgI2YZbo7k0N5A\",\"gameName\":\"C0MB4T JANNA\",\"tagLine\":\"TUFAO\",\"tier\":\"DIAMOND\"}}},{\"cd_equipe\":33,\"nome\":\"Bolinhas\",\"membros\":{\"jogador1\":{\"puuid\":\"TgzGwtxJffdDIPq6xpPf12TM381gjicoqgu9a135RY_Q7EezVy7wsfcN_u-kq6cIVNwpIVhO2Q_qwA\",\"gameName\":\"Atlas\",\"tagLine\":\"016RP\",\"tier\":\"SILVER\"},\"jogador2\":{\"puuid\":\"DAhA3LBHQ7j57dLr4RaOwKZMUJ1m9DBE2grbjkLFPeFemIouY_wKKrneVf-LfQ4_7uAyDG9DzNDdvg\",\"gameName\":\"Norvak\",\"tagLine\":\"vg8\",\"tier\":\"SILVER\"},\"jogador3\":{\"puuid\":\"3Wjxlmlwid8EhrqNw4txv90K4McKQrLWsiX-0cPuMy6w4fnrrb5u5zzBAlHY9NVZlRnNftsJubFC8g\",\"gameName\":\"Par\\u00e7aDusPar\\u00e7a\",\"tagLine\":\"BR1\",\"tier\":\"SILVER\"},\"jogador4\":{\"puuid\":\"FwzWeg2PCRot_6v655I7mtv6UGY63IA4PuMXWcMQ0ETHHDCkkNOukIvw0gnSpUscUn1DxZdbFLnrOg\",\"gameName\":\"boyceta d muleta\",\"tagLine\":\"mulet\",\"tier\":\"GOLD\"},\"jogador5\":{\"puuid\":\"ClVueM8Erx4zX9K7q-ZztL6sE_beR0rtTbSHK7TKINlANel4D6bOy4KNg1G3NKlx_8WGxSpRBAMo4g\",\"gameName\":\"Guiiler\",\"tagLine\":\"BR1\",\"tier\":\"GOLD\"}}},{\"cd_equipe\":34,\"nome\":\"Quadradinhos\",\"membros\":{\"jogador1\":{\"puuid\":\"pPTnMqXefftfcKffTzMZNo4mboN8-581Uq5ncKrkezOD_Q4_luxJwprrjyYOqw_dDkvuW92C70DD0Q\",\"gameName\":\"HD7HD7\",\"tagLine\":\"BR3\",\"tier\":\"GOLD\"},\"jogador2\":{\"puuid\":\"uJqdWlptjswbwvjJIMyja6-hEJiM8VIMXDtMFlGesXjt1vY140kf4USU99eBrzLH8lkSzGOPlikYaw\",\"gameName\":\"Phanter Clone\",\"tagLine\":\"BR1\",\"tier\":\"GOLD\"},\"jogador3\":{\"puuid\":\"WWsZVKuvjLH6tjtIRVHW2oo-UqaKoVFE-tmbGkAVTWpCsru11s25NmVejPKDUK--qnwzW9GkT9iD2Q\",\"gameName\":\"IgoorS\",\"tagLine\":\"BR1\",\"tier\":\"PLATINUM\"},\"jogador4\":{\"puuid\":\"8AwVgD4X-oO4LQ28ZLPCQYSgjPZNhI6d7zhjNXlJp2h4IgaWf-fWRy1msuR-GBpU6QdGnWWeIJ10Xg\",\"gameName\":\"L\\u00e9o Cara De L\\u00e9o\",\"tagLine\":\"NOXUS\",\"tier\":\"PLATINUM\"},\"jogador5\":{\"puuid\":\"--XeMpDg7hYVmB8jEBr9TcmUuQVWCNCp629Xods9dRe5PNunlzoA8Ztb8haitlFNQPndqG2UPJzq8w\",\"gameName\":\"AMO P DIDDY\",\"tagLine\":\"001\",\"tier\":\"PLATINUM\"}}}]', '[[{\"equipe1\":{\"cd_equipe\":33,\"nome\":\"Bolinhas\"},\"equipe2\":{\"cd_equipe\":7,\"nome\":\"asdasdasd\"},\"vencedor\":33},{\"equipe1\":{\"cd_equipe\":34,\"nome\":\"Quadradinhos\"},\"equipe2\":{\"cd_equipe\":31,\"nome\":\"kajsnc\"},\"vencedor\":34}],[{\"equipe1\":{\"cd_equipe\":33,\"nome\":\"Bolinhas\"},\"equipe2\":{\"cd_equipe\":34,\"nome\":\"Quadradinhos\"},\"vencedor\":33}]]', 1, 'Vextrion#1337', '2024-11-20 14:58:51'),
(6, 'teste', '2024-11-21 14:04:00', 4, 'FINALIZADO', '[{\"cd_equipe\":7,\"nome\":\"asdasdasd\",\"membros\":{\"jogador1\":{\"puuid\":\"ZZrsmoEaWHDp1Ug9YfVtaah8AOI_tGt9yUzHzc0ttO_-zPknJZhZciBTwwxC1BCENNb4IZk4ocQmBQ\",\"gameName\":\"FUR Wiz\",\"tagLine\":\"CBLOL\",\"tier\":\"CHALLENGER\"},\"jogador2\":{\"puuid\":\"nfffmrDXs30MOPVJsRCJ8Ets6E8Lw_gtJjEpJumsnHKORWps-SXKGIxUp5XG3naOHskLgazKBYdkOQ\",\"gameName\":\"VKS Kisee\",\"tagLine\":\"CBLOL\",\"tier\":\"CHALLENGER\"},\"jogador3\":{\"puuid\":\"IcATdGhgKv_FVQECYPdeWhE8G0ibkLoG0ZbsYmJJ-1BvpftLbuue7eGQcsIVEinv1bG-t48ntIYOEg\",\"gameName\":\"young fla\",\"tagLine\":\"furia\",\"tier\":\"CHALLENGER\"},\"jogador4\":{\"puuid\":\"FiOwAXEBBzdGTSGku_AEfhcZftWP4HsT8tQsqixTh4KUs6siWxdZS44LBYfHasn8CraCSBCHgIHzTw\",\"gameName\":\"frosty\",\"tagLine\":\"KR3\",\"tier\":\"CHALLENGER\"},\"jogador5\":{\"puuid\":\"52tfTOw0MHQM8mgCzhal4qBu9ZiEhPLSJxt41L3jPlc320uGSYZEt3AgNIU5M7yeUALIdC-m_BNuGw\",\"gameName\":\"randal\",\"tagLine\":\"ZERO1\",\"tier\":\"CHALLENGER\"}}},{\"cd_equipe\":31,\"nome\":\"kajsnc\",\"membros\":{\"jogador1\":{\"puuid\":\"32hnIjs0nLJC7AFRZo7bnWDkBIfb01Glie1aCmU7fA7-UuaR06nwO7BCRitawjNXTYlwyzj2zQI5xA\",\"gameName\":\"J\\u00e4gerwolf\",\"tagLine\":\"6969\",\"tier\":\"DIAMOND\"},\"jogador2\":{\"puuid\":\"uZBozUzvrTD8FWlcWERB9R-vkm7PYiVMtNyoVK09rfksZydCw9uenNI5LPZKQob0SbUbbgZScj7XHw\",\"gameName\":\"Espi\\u00e3o\",\"tagLine\":\"CBLOL\",\"tier\":\"DIAMOND\"},\"jogador3\":{\"puuid\":\"t88FktB2p5r5AoE55TAsSQFfjAKkj1V3jkZK84-1d1yq3IULuLsirDjtkN3iSl5BRdOcc0MEne0V2w\",\"gameName\":\"SHADYZERA1\",\"tagLine\":\"666\",\"tier\":\"DIAMOND\"},\"jogador4\":{\"puuid\":\"trJ3P0eKasOH-24vuX5RrKh95xpqwlu979xb98pdIg4_ibsxWKN__AukMdQ5ecYsQexC7gHs6PZLEg\",\"gameName\":\"cOnd\",\"tagLine\":\"BR1\",\"tier\":\"DIAMOND\"},\"jogador5\":{\"puuid\":\"WiIPjf0J1VPwZIon0MxjI2Qz6J2JeuOnspg-hKDpNbgJ2wDI7ANEsh9XKXmkvcfEGgI2YZbo7k0N5A\",\"gameName\":\"C0MB4T JANNA\",\"tagLine\":\"TUFAO\",\"tier\":\"DIAMOND\"}}},{\"cd_equipe\":33,\"nome\":\"Bolinhas\",\"membros\":{\"jogador1\":{\"puuid\":\"TgzGwtxJffdDIPq6xpPf12TM381gjicoqgu9a135RY_Q7EezVy7wsfcN_u-kq6cIVNwpIVhO2Q_qwA\",\"gameName\":\"Atlas\",\"tagLine\":\"016RP\",\"tier\":\"SILVER\"},\"jogador2\":{\"puuid\":\"DAhA3LBHQ7j57dLr4RaOwKZMUJ1m9DBE2grbjkLFPeFemIouY_wKKrneVf-LfQ4_7uAyDG9DzNDdvg\",\"gameName\":\"Norvak\",\"tagLine\":\"vg8\",\"tier\":\"SILVER\"},\"jogador3\":{\"puuid\":\"3Wjxlmlwid8EhrqNw4txv90K4McKQrLWsiX-0cPuMy6w4fnrrb5u5zzBAlHY9NVZlRnNftsJubFC8g\",\"gameName\":\"Par\\u00e7aDusPar\\u00e7a\",\"tagLine\":\"BR1\",\"tier\":\"SILVER\"},\"jogador4\":{\"puuid\":\"FwzWeg2PCRot_6v655I7mtv6UGY63IA4PuMXWcMQ0ETHHDCkkNOukIvw0gnSpUscUn1DxZdbFLnrOg\",\"gameName\":\"boyceta d muleta\",\"tagLine\":\"mulet\",\"tier\":\"GOLD\"},\"jogador5\":{\"puuid\":\"ClVueM8Erx4zX9K7q-ZztL6sE_beR0rtTbSHK7TKINlANel4D6bOy4KNg1G3NKlx_8WGxSpRBAMo4g\",\"gameName\":\"Guiiler\",\"tagLine\":\"BR1\",\"tier\":\"GOLD\"}}},{\"cd_equipe\":34,\"nome\":\"Quadradinhos\",\"membros\":{\"jogador1\":{\"puuid\":\"pPTnMqXefftfcKffTzMZNo4mboN8-581Uq5ncKrkezOD_Q4_luxJwprrjyYOqw_dDkvuW92C70DD0Q\",\"gameName\":\"HD7HD7\",\"tagLine\":\"BR3\",\"tier\":\"GOLD\"},\"jogador2\":{\"puuid\":\"uJqdWlptjswbwvjJIMyja6-hEJiM8VIMXDtMFlGesXjt1vY140kf4USU99eBrzLH8lkSzGOPlikYaw\",\"gameName\":\"Phanter Clone\",\"tagLine\":\"BR1\",\"tier\":\"GOLD\"},\"jogador3\":{\"puuid\":\"WWsZVKuvjLH6tjtIRVHW2oo-UqaKoVFE-tmbGkAVTWpCsru11s25NmVejPKDUK--qnwzW9GkT9iD2Q\",\"gameName\":\"IgoorS\",\"tagLine\":\"BR1\",\"tier\":\"PLATINUM\"},\"jogador4\":{\"puuid\":\"8AwVgD4X-oO4LQ28ZLPCQYSgjPZNhI6d7zhjNXlJp2h4IgaWf-fWRy1msuR-GBpU6QdGnWWeIJ10Xg\",\"gameName\":\"L\\u00e9o Cara De L\\u00e9o\",\"tagLine\":\"NOXUS\",\"tier\":\"PLATINUM\"},\"jogador5\":{\"puuid\":\"--XeMpDg7hYVmB8jEBr9TcmUuQVWCNCp629Xods9dRe5PNunlzoA8Ztb8haitlFNQPndqG2UPJzq8w\",\"gameName\":\"AMO P DIDDY\",\"tagLine\":\"001\",\"tier\":\"PLATINUM\"}}}]', '[[{\"equipe1\":{\"cd_equipe\":34,\"nome\":\"Quadradinhos\"},\"equipe2\":{\"cd_equipe\":31,\"nome\":\"kajsnc\"},\"vencedor\":34},{\"equipe1\":{\"cd_equipe\":33,\"nome\":\"Bolinhas\"},\"equipe2\":{\"cd_equipe\":7,\"nome\":\"asdasdasd\"},\"vencedor\":7}],[{\"equipe1\":{\"cd_equipe\":34,\"nome\":\"Quadradinhos\"},\"equipe2\":{\"cd_equipe\":7,\"nome\":\"asdasdasd\"},\"vencedor\":34}]]', 0, 'The Hellheim#hell', '2024-11-20 17:04:10');

-- --------------------------------------------------------

--
-- Estrutura para tabela `equipe`
--

CREATE TABLE `equipe` (
  `cd_equipe` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `membros` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`membros`)),
  `responsavel` varchar(255) NOT NULL,
  `tier_medio` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `equipe`
--

INSERT INTO `equipe` (`cd_equipe`, `nome`, `membros`, `responsavel`, `tier_medio`) VALUES
(7, 'asdasdasd', '{\n    \"jogador1\": {\n        \"puuid\": \"ZZrsmoEaWHDp1Ug9YfVtaah8AOI_tGt9yUzHzc0ttO_-zPknJZhZciBTwwxC1BCENNb4IZk4ocQmBQ\",\n        \"gameName\": \"FUR Wiz\",\n        \"tagLine\": \"CBLOL\",\n        \"tier\": \"CHALLENGER\"\n    },\n    \"jogador2\": {\n        \"puuid\": \"nfffmrDXs30MOPVJsRCJ8Ets6E8Lw_gtJjEpJumsnHKORWps-SXKGIxUp5XG3naOHskLgazKBYdkOQ\",\n        \"gameName\": \"VKS Kisee\",\n        \"tagLine\": \"CBLOL\",\n        \"tier\": \"CHALLENGER\"\n    },\n    \"jogador3\": {\n        \"puuid\": \"IcATdGhgKv_FVQECYPdeWhE8G0ibkLoG0ZbsYmJJ-1BvpftLbuue7eGQcsIVEinv1bG-t48ntIYOEg\",\n        \"gameName\": \"young fla\",\n        \"tagLine\": \"furia\",\n        \"tier\": \"CHALLENGER\"\n    },\n    \"jogador4\": {\n        \"puuid\": \"FiOwAXEBBzdGTSGku_AEfhcZftWP4HsT8tQsqixTh4KUs6siWxdZS44LBYfHasn8CraCSBCHgIHzTw\",\n        \"gameName\": \"frosty\",\n        \"tagLine\": \"KR3\",\n        \"tier\": \"CHALLENGER\"\n    },\n    \"jogador5\": {\n        \"puuid\": \"52tfTOw0MHQM8mgCzhal4qBu9ZiEhPLSJxt41L3jPlc320uGSYZEt3AgNIU5M7yeUALIdC-m_BNuGw\",\n        \"gameName\": \"randal\",\n        \"tagLine\": \"ZERO1\",\n        \"tier\": \"CHALLENGER\"\n    }\n}', 'FUR Wiz#CBLOL', 'CHALLENGER'),
(31, 'kajsnc', '{\n    \"jogador1\": {\n        \"puuid\": \"32hnIjs0nLJC7AFRZo7bnWDkBIfb01Glie1aCmU7fA7-UuaR06nwO7BCRitawjNXTYlwyzj2zQI5xA\",\n        \"gameName\": \"J\\u00e4gerwolf\",\n        \"tagLine\": \"6969\",\n        \"tier\": \"DIAMOND\"\n    },\n    \"jogador2\": {\n        \"puuid\": \"uZBozUzvrTD8FWlcWERB9R-vkm7PYiVMtNyoVK09rfksZydCw9uenNI5LPZKQob0SbUbbgZScj7XHw\",\n        \"gameName\": \"Espi\\u00e3o\",\n        \"tagLine\": \"CBLOL\",\n        \"tier\": \"DIAMOND\"\n    },\n    \"jogador3\": {\n        \"puuid\": \"t88FktB2p5r5AoE55TAsSQFfjAKkj1V3jkZK84-1d1yq3IULuLsirDjtkN3iSl5BRdOcc0MEne0V2w\",\n        \"gameName\": \"SHADYZERA1\",\n        \"tagLine\": \"666\",\n        \"tier\": \"DIAMOND\"\n    },\n    \"jogador4\": {\n        \"puuid\": \"trJ3P0eKasOH-24vuX5RrKh95xpqwlu979xb98pdIg4_ibsxWKN__AukMdQ5ecYsQexC7gHs6PZLEg\",\n        \"gameName\": \"cOnd\",\n        \"tagLine\": \"BR1\",\n        \"tier\": \"DIAMOND\"\n    },\n    \"jogador5\": {\n        \"puuid\": \"WiIPjf0J1VPwZIon0MxjI2Qz6J2JeuOnspg-hKDpNbgJ2wDI7ANEsh9XKXmkvcfEGgI2YZbo7k0N5A\",\n        \"gameName\": \"C0MB4T JANNA\",\n        \"tagLine\": \"TUFAO\",\n        \"tier\": \"DIAMOND\"\n    }\n}', 'Vextrion#1337', 'DIAMOND'),
(33, 'Bolinhas', '{\n    \"jogador1\": {\n        \"puuid\": \"TgzGwtxJffdDIPq6xpPf12TM381gjicoqgu9a135RY_Q7EezVy7wsfcN_u-kq6cIVNwpIVhO2Q_qwA\",\n        \"gameName\": \"Atlas\",\n        \"tagLine\": \"016RP\",\n        \"tier\": \"SILVER\"\n    },\n    \"jogador2\": {\n        \"puuid\": \"DAhA3LBHQ7j57dLr4RaOwKZMUJ1m9DBE2grbjkLFPeFemIouY_wKKrneVf-LfQ4_7uAyDG9DzNDdvg\",\n        \"gameName\": \"Norvak\",\n        \"tagLine\": \"vg8\",\n        \"tier\": \"SILVER\"\n    },\n    \"jogador3\": {\n        \"puuid\": \"3Wjxlmlwid8EhrqNw4txv90K4McKQrLWsiX-0cPuMy6w4fnrrb5u5zzBAlHY9NVZlRnNftsJubFC8g\",\n        \"gameName\": \"Par\\u00e7aDusPar\\u00e7a\",\n        \"tagLine\": \"BR1\",\n        \"tier\": \"SILVER\"\n    },\n    \"jogador4\": {\n        \"puuid\": \"FwzWeg2PCRot_6v655I7mtv6UGY63IA4PuMXWcMQ0ETHHDCkkNOukIvw0gnSpUscUn1DxZdbFLnrOg\",\n        \"gameName\": \"boyceta d muleta\",\n        \"tagLine\": \"mulet\",\n        \"tier\": \"GOLD\"\n    },\n    \"jogador5\": {\n        \"puuid\": \"ClVueM8Erx4zX9K7q-ZztL6sE_beR0rtTbSHK7TKINlANel4D6bOy4KNg1G3NKlx_8WGxSpRBAMo4g\",\n        \"gameName\": \"Guiiler\",\n        \"tagLine\": \"BR1\",\n        \"tier\": \"GOLD\"\n    }\n}', 'Pau Molin#BR1', 'SILVER'),
(34, 'Quadradinhos', '{\n    \"jogador1\": {\n        \"puuid\": \"pPTnMqXefftfcKffTzMZNo4mboN8-581Uq5ncKrkezOD_Q4_luxJwprrjyYOqw_dDkvuW92C70DD0Q\",\n        \"gameName\": \"HD7HD7\",\n        \"tagLine\": \"BR3\",\n        \"tier\": \"GOLD\"\n    },\n    \"jogador2\": {\n        \"puuid\": \"uJqdWlptjswbwvjJIMyja6-hEJiM8VIMXDtMFlGesXjt1vY140kf4USU99eBrzLH8lkSzGOPlikYaw\",\n        \"gameName\": \"Phanter Clone\",\n        \"tagLine\": \"BR1\",\n        \"tier\": \"GOLD\"\n    },\n    \"jogador3\": {\n        \"puuid\": \"WWsZVKuvjLH6tjtIRVHW2oo-UqaKoVFE-tmbGkAVTWpCsru11s25NmVejPKDUK--qnwzW9GkT9iD2Q\",\n        \"gameName\": \"IgoorS\",\n        \"tagLine\": \"BR1\",\n        \"tier\": \"PLATINUM\"\n    },\n    \"jogador4\": {\n        \"puuid\": \"8AwVgD4X-oO4LQ28ZLPCQYSgjPZNhI6d7zhjNXlJp2h4IgaWf-fWRy1msuR-GBpU6QdGnWWeIJ10Xg\",\n        \"gameName\": \"L\\u00e9o Cara De L\\u00e9o\",\n        \"tagLine\": \"NOXUS\",\n        \"tier\": \"PLATINUM\"\n    },\n    \"jogador5\": {\n        \"puuid\": \"--XeMpDg7hYVmB8jEBr9TcmUuQVWCNCp629Xods9dRe5PNunlzoA8Ztb8haitlFNQPndqG2UPJzq8w\",\n        \"gameName\": \"AMO P DIDDY\",\n        \"tagLine\": \"001\",\n        \"tier\": \"PLATINUM\"\n    }\n}', 'The Hellheim#hell', 'PLATINUM'),
(46, 'Equipe Teste123', '{\"jogador1\":{\"gameName\":\"Test1\",\"tagLine\":\"BR1\",\"tier\":\"GOLD\"},\"jogador2\":{\"gameName\":\"Test2\",\"tagLine\":\"BR1\",\"tier\":\"GOLD\"},\"jogador3\":{\"gameName\":\"Test3\",\"tagLine\":\"BR1\",\"tier\":\"GOLD\"},\"jogador4\":{\"gameName\":\"Test4\",\"tagLine\":\"BR1\",\"tier\":\"GOLD\"},\"jogador5\":{\"gameName\":\"Test5\",\"tagLine\":\"BR1\",\"tier\":\"GOLD\"}}', 'TestUser#BR1', 'GOLD');

-- --------------------------------------------------------

--
-- Estrutura para tabela `feedback`
--

CREATE TABLE `feedback` (
  `cd_feedback` int(11) NOT NULL,
  `cd_usuario` int(11) DEFAULT NULL,
  `mensagem` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `cd_usuario` int(11) NOT NULL,
  `puuid_usuario` varchar(255) NOT NULL,
  `gameName_usuario` varchar(255) NOT NULL,
  `tagLine_usuario` varchar(255) NOT NULL,
  `email_usuario` varchar(255) NOT NULL,
  `medalhas_usuario` int(11) DEFAULT NULL,
  `senha_usuario` varchar(255) NOT NULL,
  `organizador_usuario` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`cd_usuario`, `puuid_usuario`, `gameName_usuario`, `tagLine_usuario`, `email_usuario`, `medalhas_usuario`, `senha_usuario`, `organizador_usuario`) VALUES
(6, 'b85AHeSOEekwbA1GmLChRvi9PbK2M8wlGDWHyPRTymlTuMlgGOANDG5qbwomGmYjcLx7cV423dZ6HQ', 'Vextrion', '1337', 'teste@teste.com', NULL, 'jMo1ex2]Q[ecHoW4', 0),
(7, '9Za_GwBZZLwxsAI_skLmw64prGCXkvibnJbljb4X8q6v2nKOkQgwo_XxelxrjgYa0CzpeLY0vO3TBg', 'Pau Molin', 'BR1', 'asd@asd.com', NULL, 'jMo1ex2]Q[ecHoW4', 0),
(8, 'DBcfmPVTZBw5u3Kb_kHqrZklAZnNA5Ajhkr8z9e3hxm49iRJxFm3_MHYFZ_k_sgWhnaQfKbFYT3CZg', 'The Hellheim', 'hell', 'teste2@teste2.com', NULL, 'jMo1ex2]Q[ecHoW4', 0),
(9, '5CltpHIxrSCcRlu2ZMc78Ttjs37-QpSJeCW9cV6vds3HzTiUUbYgjfJWP_Ope-2JtXBO6_Ib9wxrSQ', 'Pau Tortin', 'BR1', 'teste3@teste3.com', NULL, 'jMo1ex2]Q[ecHoW4', 0),
(24, 'abc123', 'TestUser', 'BR1', 'novo@usuario.com', NULL, 'senha123', 0),
(25, '', '', '', 'usuario@duplicado.com', NULL, 'senha123', 0),
(27, '5CltpHIxrSCcRlu2ZMc78Ttjs37-QpSJeTJ9cV6vds3HzTiUUbYgjfJWP_Ope-2JtXBO6_Ib9wxrSQ', '', '', 'usuario@teste.com', NULL, 'senha123', 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `campeonatos`
--
ALTER TABLE `campeonatos`
  ADD PRIMARY KEY (`cd_campeonato`);

--
-- Índices de tabela `equipe`
--
ALTER TABLE `equipe`
  ADD PRIMARY KEY (`cd_equipe`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`cd_feedback`),
  ADD KEY `cd_usuario` (`cd_usuario`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`cd_usuario`),
  ADD UNIQUE KEY `puuid_usuario` (`puuid_usuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `campeonatos`
--
ALTER TABLE `campeonatos`
  MODIFY `cd_campeonato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `equipe`
--
ALTER TABLE `equipe`
  MODIFY `cd_equipe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de tabela `feedback`
--
ALTER TABLE `feedback`
  MODIFY `cd_feedback` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `cd_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`cd_usuario`) REFERENCES `usuarios` (`cd_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
