-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 20, 2025 at 11:05 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ql_mamnon_nhom11`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `id` int NOT NULL,
  `activityname` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`id`, `activityname`) VALUES
(1, 'Đón trẻ, trò chuyện buổi sáng'),
(2, 'Tập thể dục buổi sáng'),
(3, 'Hoạt động học tập trung'),
(4, 'Hoạt động Nghệ thuật'),
(5, 'Ăn nhẹ'),
(6, 'Chơi tự do trong lớp'),
(7, 'Chuẩn bị và ăn trưa'),
(8, 'Ngủ trưa'),
(9, 'Dậy, vệ sinh cá nhân'),
(10, 'Ôn tập'),
(11, 'Hoạt động vui chơi'),
(12, 'Hoạt động cuối ngày'),
(13, 'Chuẩn bị ra về');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int NOT NULL,
  `studentid` int DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` text,
  `reason` text,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `studentid`, `date`, `status`, `reason`, `notes`) VALUES
(1, 3, '2025-01-09', '1', '', ''),
(2, 4, '2025-01-09', '1', '', 'đi hơi muộn'),
(3, 7, '2025-01-09', '1', '', ''),
(27, 19, '2025-01-09', '0', '', ''),
(28, 1, '2025-01-09', '1', '', ''),
(29, 20, '2025-01-09', '0', 'bị ốm', ''),
(30, 21, '2025-01-09', '1', '', ''),
(31, 22, '2025-01-09', '1', '', 'đi muộn'),
(32, 9, '2025-01-09', '1', '', ''),
(33, 10, '2025-01-09', '1', '', ''),
(34, 11, '2025-01-09', '1', 'dday kà leminhtian', ''),
(35, 12, '2025-01-09', '0', '', ''),
(36, 14, '2025-01-09', '0', '', ''),
(37, 15, '2025-01-09', '0', '', ''),
(38, 16, '2025-01-09', '0', '', ''),
(39, 17, '2025-01-09', '0', 'bị ốm', ''),
(40, 18, '2025-01-09', '0', '', ''),
(41, 23, '2025-01-09', '0', '', ''),
(42, 24, '2025-01-09', '0', '', ''),
(43, 25, '2025-01-09', '0', '', ''),
(44, 26, '2025-01-09', '0', '', ''),
(45, 27, '2025-01-09', '0', '', ''),
(46, 3, '2025-01-10', '1', '', ''),
(47, 4, '2025-01-10', '1', '', ''),
(48, 7, '2025-01-10', '1', '', ''),
(49, 9, '2025-01-10', '1', '', ''),
(50, 10, '2025-01-10', '0', '', ''),
(51, 11, '2025-01-10', '1', '', ''),
(52, 12, '2025-01-10', '1', '', ''),
(53, 14, '2025-01-10', '1', '', ''),
(54, 15, '2025-01-10', '1', '', ''),
(55, 16, '2025-01-10', '0', '', ''),
(56, 17, '2025-01-10', '1', '', ''),
(57, 18, '2025-01-10', '1', '', ''),
(58, 19, '2025-01-10', '1', '', ''),
(59, 1, '2025-01-10', '1', '', ''),
(60, 20, '2025-01-10', '1', '', ''),
(61, 21, '2025-01-10', '0', 'om', ''),
(62, 22, '2025-01-10', '0', 'om', ''),
(63, 23, '2025-01-10', '0', 'om', ''),
(64, 24, '2025-01-10', '0', '', ''),
(65, 25, '2025-01-10', '0', '', ''),
(66, 26, '2025-01-10', '0', '', ''),
(67, 27, '2025-01-10', '0', '', ''),
(68, 3, '2025-01-12', '1', '', ''),
(69, 4, '2025-01-12', '1', '', ''),
(70, 7, '2025-01-12', '1', '', ''),
(71, 9, '2025-01-12', '1', '', ''),
(72, 10, '2025-01-12', '0', '', ''),
(73, 11, '2025-01-12', '0', '', ''),
(74, 12, '2025-01-12', '0', '', ''),
(75, 14, '2025-01-12', '0', '', ''),
(76, 15, '2025-01-12', '0', '', ''),
(77, 16, '2025-01-12', '0', '', ''),
(78, 17, '2025-01-12', '0', '', ''),
(79, 18, '2025-01-12', '0', '', ''),
(80, 19, '2025-01-12', '0', '', ''),
(81, 1, '2025-01-12', '0', '', ''),
(82, 20, '2025-01-12', '0', '', ''),
(83, 21, '2025-01-12', '1', '', ''),
(84, 22, '2025-01-12', '1', '', ''),
(85, 23, '2025-01-12', '1', '', ''),
(86, 24, '2025-01-12', '0', '', ''),
(87, 25, '2025-01-12', '1', '', ''),
(88, 26, '2025-01-12', '1', '', ''),
(89, 27, '2025-01-12', '1', '', ''),
(90, 3, '2025-01-13', '1', '', ''),
(91, 4, '2025-01-13', '1', '', ''),
(92, 7, '2025-01-13', '1', '', ''),
(93, 9, '2025-01-13', '1', '', ''),
(94, 10, '2025-01-13', '1', '', ''),
(95, 11, '2025-01-13', '0', '', ''),
(96, 12, '2025-01-13', '1', '', ''),
(97, 14, '2025-01-13', '1', '', ''),
(98, 15, '2025-01-13', '0', '', ''),
(99, 16, '2025-01-13', '0', '', ''),
(100, 17, '2025-01-13', '0', '', ''),
(101, 18, '2025-01-13', '0', '', ''),
(102, 19, '2025-01-13', '0', '', ''),
(103, 1, '2025-01-13', '0', '', ''),
(104, 20, '2025-01-13', '0', '', ''),
(105, 21, '2025-01-13', '0', '', ''),
(106, 22, '2025-01-13', '0', '', ''),
(107, 23, '2025-01-13', '0', '', ''),
(108, 24, '2025-01-13', '0', '', ''),
(109, 25, '2025-01-13', '0', '', ''),
(110, 26, '2025-01-13', '1', '', ''),
(111, 27, '2025-01-13', '1', '', ''),
(112, 3, '2025-01-14', '0', '', ''),
(113, 4, '2025-01-14', '0', '', ''),
(114, 7, '2025-01-14', '0', '', ''),
(115, 9, '2025-01-14', '1', '', ''),
(116, 10, '2025-01-14', '1', '', ''),
(117, 11, '2025-01-14', '0', '', ''),
(118, 12, '2025-01-14', '0', '', ''),
(119, 14, '2025-01-14', '0', '', ''),
(120, 15, '2025-01-14', '0', '', ''),
(121, 16, '2025-01-14', '0', '', ''),
(122, 17, '2025-01-14', '1', '', ''),
(123, 18, '2025-01-14', '0', '', ''),
(124, 19, '2025-01-14', '1', '', ''),
(125, 1, '2025-01-14', '1', '', ''),
(126, 20, '2025-01-14', '1', '', ''),
(127, 21, '2025-01-14', '1', '', ''),
(128, 22, '2025-01-14', '1', '', ''),
(129, 23, '2025-01-14', '1', '', ''),
(130, 24, '2025-01-14', '1', '', ''),
(131, 25, '2025-01-14', '1', '', ''),
(132, 26, '2025-01-14', '1', '', ''),
(133, 27, '2025-01-14', '1', '', ''),
(134, 3, '2025-01-15', '1', '', ''),
(135, 4, '2025-01-15', '1', '', ''),
(136, 7, '2025-01-15', '1', '', ''),
(137, 9, '2025-01-15', '1', '', ''),
(138, 10, '2025-01-15', '1', '', ''),
(139, 11, '2025-01-15', '1', '', ''),
(140, 12, '2025-01-15', '0', '', ''),
(141, 14, '2025-01-15', '0', '', ''),
(142, 15, '2025-01-15', '0', '', ''),
(143, 16, '2025-01-15', '0', '', ''),
(144, 17, '2025-01-15', '1', '', ''),
(145, 18, '2025-01-15', '0', '', ''),
(146, 19, '2025-01-15', '0', '', ''),
(147, 1, '2025-01-15', '0', '', ''),
(148, 20, '2025-01-15', '0', '', ''),
(149, 21, '2025-01-15', '0', '', ''),
(150, 22, '2025-01-15', '0', '', ''),
(151, 23, '2025-01-15', '1', '', ''),
(152, 24, '2025-01-15', '1', '', ''),
(153, 25, '2025-01-15', '0', '', ''),
(154, 26, '2025-01-15', '1', '', ''),
(155, 27, '2025-01-15', '1', '', ''),
(156, 28, '2025-01-15', '0', 'ốm', ''),
(157, 29, '2025-01-15', '1', '', ''),
(158, 30, '2025-01-15', '1', '', ''),
(159, 31, '2025-01-15', '1', '', ''),
(160, 32, '2025-01-15', '0', '', 'đi muộn 30p'),
(161, 33, '2025-01-15', '0', '', ''),
(162, 34, '2025-01-15', '0', '', ''),
(163, 3, '2025-01-16', '0', '', ''),
(164, 4, '2025-01-16', '1', '', ''),
(165, 7, '2025-01-16', '0', '', ''),
(166, 9, '2025-01-16', '1', '', ''),
(167, 10, '2025-01-16', '1', '', ''),
(168, 11, '2025-01-16', '0', '', ''),
(169, 12, '2025-01-16', '0', '', ''),
(170, 14, '2025-01-16', '0', '', ''),
(171, 15, '2025-01-16', '0', '', ''),
(172, 16, '2025-01-16', '0', '', ''),
(173, 17, '2025-01-16', '1', '', ''),
(174, 18, '2025-01-16', '0', '', ''),
(175, 19, '2025-01-16', '0', '', ''),
(176, 1, '2025-01-16', '0', '', ''),
(177, 20, '2025-01-16', '0', '', ''),
(178, 21, '2025-01-16', '0', '', ''),
(179, 22, '2025-01-16', '0', '', ''),
(180, 23, '2025-01-16', '0', '', ''),
(181, 24, '2025-01-16', '0', '', ''),
(182, 25, '2025-01-16', '0', '', ''),
(183, 26, '2025-01-16', '0', '', ''),
(184, 27, '2025-01-16', '0', '', ''),
(185, 28, '2025-01-16', '0', '', ''),
(186, 29, '2025-01-16', '0', '', ''),
(187, 30, '2025-01-16', '0', '', ''),
(188, 31, '2025-01-16', '0', '', ''),
(189, 32, '2025-01-16', '0', '', ''),
(190, 33, '2025-01-16', '0', '', ''),
(191, 34, '2025-01-16', '0', '', ''),
(192, 3, '2025-01-19', '1', '', ''),
(193, 4, '2025-01-19', '1', '', ''),
(194, 7, '2025-01-19', '1', '', ''),
(195, 9, '2025-01-19', '1', '', ''),
(196, 10, '2025-01-19', '1', '', ''),
(197, 11, '2025-01-19', '0', 'bị ốm', ''),
(198, 12, '2025-01-19', '1', '', ''),
(199, 14, '2025-01-19', '1', '', ''),
(200, 15, '2025-01-19', '1', '', ''),
(201, 16, '2025-01-19', '1', '', ''),
(202, 17, '2025-01-19', '1', '', 'đi muộn 30p'),
(203, 18, '2025-01-19', '0', '', ''),
(204, 19, '2025-01-19', '0', '', ''),
(205, 1, '2025-01-19', '0', '', ''),
(206, 20, '2025-01-19', '0', '', ''),
(207, 21, '2025-01-19', '0', '', ''),
(208, 22, '2025-01-19', '0', '', ''),
(209, 23, '2025-01-19', '0', '', ''),
(210, 24, '2025-01-19', '0', '', ''),
(211, 25, '2025-01-19', '0', '', ''),
(212, 26, '2025-01-19', '0', '', ''),
(213, 27, '2025-01-19', '0', '', ''),
(214, 28, '2025-01-19', '0', '', ''),
(215, 29, '2025-01-19', '0', '', ''),
(216, 30, '2025-01-19', '0', '', ''),
(217, 31, '2025-01-19', '0', '', ''),
(218, 32, '2025-01-19', '0', '', ''),
(219, 33, '2025-01-19', '0', '', ''),
(220, 34, '2025-01-19', '0', '', ''),
(221, 3, '2025-02-04', '1', '', ''),
(222, 4, '2025-02-04', '1', '', ''),
(223, 7, '2025-02-04', '1', '', ''),
(224, 9, '2025-02-04', '1', '', ''),
(225, 10, '2025-02-04', '1', '', ''),
(226, 11, '2025-02-04', '1', '', ''),
(227, 12, '2025-02-04', '0', '', ''),
(228, 14, '2025-02-04', '0', '', ''),
(229, 15, '2025-02-04', '0', '', ''),
(230, 16, '2025-02-04', '0', '', ''),
(231, 17, '2025-02-04', '0', '', ''),
(232, 18, '2025-02-04', '0', '', ''),
(233, 19, '2025-02-04', '0', '', ''),
(234, 1, '2025-02-04', '0', '', ''),
(235, 20, '2025-02-04', '0', 'om', ''),
(236, 21, '2025-02-04', '0', 'om', ''),
(237, 22, '2025-02-04', '0', 'ooms', ''),
(238, 23, '2025-02-04', '1', '', ''),
(239, 24, '2025-02-04', '1', '', ''),
(240, 25, '2025-02-04', '1', '', ''),
(241, 26, '2025-02-04', '0', '', ''),
(242, 27, '2025-02-04', '0', '', ''),
(243, 28, '2025-02-04', '0', '', ''),
(244, 29, '2025-02-04', '0', '', ''),
(245, 30, '2025-02-04', '0', '', ''),
(246, 31, '2025-02-04', '0', '', ''),
(247, 32, '2025-02-04', '0', '', ''),
(248, 33, '2025-02-04', '0', '', ''),
(249, 34, '2025-02-04', '0', '', ''),
(250, 3, '2025-02-09', '1', '', ''),
(251, 4, '2025-02-09', '0', 'bị ốm', ''),
(252, 7, '2025-02-09', '1', '', ''),
(253, 9, '2025-02-09', '1', '', ''),
(254, 10, '2025-02-09', '1', '', ''),
(255, 11, '2025-02-09', '1', '', ''),
(256, 12, '2025-02-09', '1', '', ''),
(257, 14, '2025-02-09', '1', '', ''),
(258, 15, '2025-02-09', '1', '', ''),
(259, 16, '2025-02-09', '1', '', 'đi muộn'),
(260, 17, '2025-02-09', '1', '', ''),
(261, 18, '2025-02-09', '1', '', ''),
(262, 19, '2025-02-09', '0', '', ''),
(263, 1, '2025-02-09', '0', '', ''),
(264, 20, '2025-02-09', '0', '', ''),
(265, 21, '2025-02-09', '0', '', ''),
(266, 22, '2025-02-09', '0', '', ''),
(267, 23, '2025-02-09', '0', '', ''),
(268, 24, '2025-02-09', '0', '', ''),
(269, 25, '2025-02-09', '0', '', ''),
(270, 26, '2025-02-09', '0', '', ''),
(271, 27, '2025-02-09', '0', '', ''),
(272, 28, '2025-02-09', '1', '', ''),
(273, 29, '2025-02-09', '1', '', ''),
(274, 30, '2025-02-09', '1', '', ''),
(275, 31, '2025-02-09', '1', '', ''),
(276, 32, '2025-02-09', '1', '', ''),
(277, 33, '2025-02-09', '0', '', ''),
(278, 34, '2025-02-09', '0', '', ''),
(279, 3, '2025-02-13', '1', '', ''),
(280, 4, '2025-02-13', '1', '', ''),
(281, 7, '2025-02-13', '1', '', ''),
(282, 9, '2025-02-13', '1', '', ''),
(283, 10, '2025-02-13', '1', '', ''),
(284, 11, '2025-02-13', '1', '', ''),
(285, 12, '2025-02-13', '1', '', ''),
(286, 14, '2025-02-13', '0', '', ''),
(287, 15, '2025-02-13', '0', '', ''),
(288, 16, '2025-02-13', '1', '', ''),
(289, 17, '2025-02-13', '1', '', ''),
(290, 18, '2025-02-13', '1', '', ''),
(291, 19, '2025-02-13', '1', '', ''),
(292, 1, '2025-02-13', '1', '', ''),
(293, 20, '2025-02-13', '1', '', ''),
(294, 21, '2025-02-13', '0', '', ''),
(295, 22, '2025-02-13', '0', '', ''),
(296, 23, '2025-02-13', '0', '', ''),
(297, 24, '2025-02-13', '0', '', ''),
(298, 25, '2025-02-13', '0', '', ''),
(299, 26, '2025-02-13', '0', '', ''),
(300, 27, '2025-02-13', '0', '', ''),
(301, 28, '2025-02-13', '0', 'bị ốm', ''),
(302, 29, '2025-02-13', '1', '', ''),
(303, 30, '2025-02-13', '1', '', ''),
(304, 31, '2025-02-13', '1', '', ''),
(305, 32, '2025-02-13', '1', '', ''),
(306, 33, '2025-02-13', '0', '', ''),
(307, 34, '2025-02-13', '0', '', ''),
(308, 35, '2025-02-13', '0', '', ''),
(309, 36, '2025-02-13', '0', '', ''),
(310, 37, '2025-02-13', '0', '', ''),
(311, 38, '2025-02-13', '0', '', ''),
(312, 39, '2025-02-13', '0', '', ''),
(313, 3, '2025-02-14', '1', '', ''),
(314, 4, '2025-02-14', '1', '', ''),
(315, 7, '2025-02-14', '1', '', ''),
(316, 9, '2025-02-14', '1', '', ''),
(317, 10, '2025-02-14', '1', '', ''),
(318, 11, '2025-02-14', '1', '', ''),
(319, 12, '2025-02-14', '1', '', ''),
(320, 14, '2025-02-14', '1', '', ''),
(321, 15, '2025-02-14', '1', '', ''),
(322, 16, '2025-02-14', '0', '', ''),
(323, 17, '2025-02-14', '0', '', ''),
(324, 18, '2025-02-14', '0', '', ''),
(325, 19, '2025-02-14', '0', '', ''),
(326, 1, '2025-02-14', '0', '', ''),
(327, 20, '2025-02-14', '0', '', ''),
(328, 21, '2025-02-14', '0', '', ''),
(329, 22, '2025-02-14', '0', '', ''),
(330, 23, '2025-02-14', '0', '', ''),
(331, 24, '2025-02-14', '0', '', ''),
(332, 25, '2025-02-14', '0', '', ''),
(333, 26, '2025-02-14', '0', '', ''),
(334, 27, '2025-02-14', '0', '', ''),
(335, 28, '2025-02-14', '1', '', ''),
(336, 29, '2025-02-14', '1', '', ''),
(337, 30, '2025-02-14', '1', '', ''),
(338, 31, '2025-02-14', '1', '', ''),
(339, 32, '2025-02-14', '1', '', ''),
(340, 33, '2025-02-14', '1', '', ''),
(341, 34, '2025-02-14', '1', '', ''),
(342, 35, '2025-02-14', '1', '', ''),
(343, 36, '2025-02-14', '1', '', ''),
(344, 37, '2025-02-14', '1', '', ''),
(345, 38, '2025-02-14', '0', '', ''),
(346, 39, '2025-02-14', '0', '', ''),
(347, 3, '2025-02-15', '0', '', ''),
(348, 4, '2025-02-15', '0', '', ''),
(349, 7, '2025-02-15', '0', '', ''),
(350, 9, '2025-02-15', '0', '', ''),
(351, 10, '2025-02-15', '0', '', ''),
(352, 11, '2025-02-15', '0', '', ''),
(353, 12, '2025-02-15', '0', '', ''),
(354, 14, '2025-02-15', '0', '', ''),
(355, 15, '2025-02-15', '0', '', ''),
(356, 16, '2025-02-15', '0', '', ''),
(357, 17, '2025-02-15', '0', '', ''),
(358, 18, '2025-02-15', '0', '', ''),
(359, 19, '2025-02-15', '0', '', ''),
(360, 1, '2025-02-15', '0', '', ''),
(361, 20, '2025-02-15', '0', '', ''),
(362, 21, '2025-02-15', '0', '', ''),
(363, 22, '2025-02-15', '0', '', ''),
(364, 23, '2025-02-15', '0', '', ''),
(365, 24, '2025-02-15', '0', '', ''),
(366, 25, '2025-02-15', '0', '', ''),
(367, 26, '2025-02-15', '0', '', ''),
(368, 27, '2025-02-15', '0', '', ''),
(369, 28, '2025-02-15', '0', '', ''),
(370, 29, '2025-02-15', '0', '', ''),
(371, 30, '2025-02-15', '0', '', ''),
(372, 31, '2025-02-15', '0', '', ''),
(373, 32, '2025-02-15', '0', '', ''),
(374, 33, '2025-02-15', '0', '', ''),
(375, 34, '2025-02-15', '0', '', ''),
(376, 35, '2025-02-15', '0', '', ''),
(377, 36, '2025-02-15', '0', '', ''),
(378, 37, '2025-02-15', '0', '', ''),
(379, 38, '2025-02-15', '0', '', ''),
(380, 39, '2025-02-15', '0', '', ''),
(381, 3, '2025-02-19', '1', '', ''),
(382, 4, '2025-02-19', '1', '', ''),
(383, 7, '2025-02-19', '0', '', ''),
(384, 9, '2025-02-19', '1', '', ''),
(385, 10, '2025-02-19', '1', '', ''),
(386, 11, '2025-02-19', '0', '', ''),
(387, 12, '2025-02-19', '0', '', ''),
(388, 14, '2025-02-19', '0', '', ''),
(389, 15, '2025-02-19', '0', '', ''),
(390, 16, '2025-02-19', '0', '', ''),
(391, 17, '2025-02-19', '0', '', ''),
(392, 18, '2025-02-19', '0', '', ''),
(393, 19, '2025-02-19', '0', '', ''),
(394, 1, '2025-02-19', '0', '', ''),
(395, 20, '2025-02-19', '0', '', ''),
(396, 21, '2025-02-19', '0', '', ''),
(397, 22, '2025-02-19', '0', '', ''),
(398, 23, '2025-02-19', '0', '', ''),
(399, 24, '2025-02-19', '0', '', ''),
(400, 25, '2025-02-19', '0', '', ''),
(401, 26, '2025-02-19', '0', '', ''),
(402, 27, '2025-02-19', '0', '', ''),
(403, 28, '2025-02-19', '0', '', ''),
(404, 29, '2025-02-19', '0', '', ''),
(405, 30, '2025-02-19', '0', '', ''),
(406, 31, '2025-02-19', '0', '', ''),
(407, 32, '2025-02-19', '0', '', ''),
(408, 33, '2025-02-19', '0', '', ''),
(409, 34, '2025-02-19', '0', '', ''),
(410, 35, '2025-02-19', '0', '', ''),
(411, 36, '2025-02-19', '0', '', ''),
(412, 37, '2025-02-19', '0', '', ''),
(413, 38, '2025-02-19', '0', '', ''),
(414, 39, '2025-02-19', '0', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int NOT NULL,
  `ClassName` varchar(100) NOT NULL,
  `TeacherId` int DEFAULT NULL,
  `Quantity` int NOT NULL,
  `GradeID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `ClassName`, `TeacherId`, `Quantity`, `GradeID`) VALUES
(1, 'b2', 3, 23, 2),
(2, 'b4', 7, 27, 2),
(3, 'b3', 4, 25, 2),
(4, 'a1', 6, 32, 3),
(6, 'c1', 8, 12, 1),
(7, 'c2', 9, 23, 1),
(9, 'c4', 7, 24, 1),
(10, 'c5', 25, 22, 1),
(11, 'c6', 11, 25, 3),
(14, 'a3', 4, 26, 3),
(15, 'a4', 7, 24, 3),
(16, 'a5', 8, 19, 3),
(17, 'b5', 9, 23, 2),
(19, 'b1', 12, 27, 2),
(20, 'a6', 9, 12, 3),
(21, 'a2', 19, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `name`, `phone`, `email`, `content`) VALUES
(1, 'AN Hoai Linh', '0964135761', 'anlinh3011@gmail.com', 'hhhhhhhhhhhhhhh'),
(2, 'lanhh', '09988', 'linh@gmail.com', 'ffffffffffffff'),
(3, 'lanh', '0987', 'an@gmail.com', 'aaaaaaaaaaaaaaaaaaaaaaaaaa'),
(4, 'AN Hoai Linh', '0964135761', 'anlinh3011@gmail.com', 'aaaaaaaaaaaaaaaa');

-- --------------------------------------------------------

--
-- Table structure for table `contacttt`
--

CREATE TABLE `contacttt` (
  `id` int NOT NULL,
  `hotline` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacttt`
--

INSERT INTO `contacttt` (`id`, `hotline`, `email`) VALUES
(1, '0914267891', 'mamnon11@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description1` text,
  `description2` text,
  `image1` varchar(255) DEFAULT NULL,
  `image2` varchar(255) DEFAULT NULL,
  `image3` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `name`, `title`, `description1`, `description2`, `image1`, `image2`, `image3`) VALUES
(1, 'Trường Mầm Non 11', 'Nơi trải nghiệm hạnh phúc cùng con', 'Trường Mầm non 11 Montessori được tạo dựng từ một nền tảng vững chắc của một ngôi trường hạnh phúc – nơi mỗi trẻ em được chăm sóc, hướng dẫn và đồng hành trên hành trình phát triển bản thân và hoàn thiện nhân cách.', 'Đồng thời giúp con nuôi dưỡng sự tự tin, tự lập, giúp con yêu thương và có trách nhiệm với bản thân, con được trang bị đầy đủ kỹ năng để phát huy tối đa tiềm năng. Hơn hết, con là một người hạnh phúc.', 'images/content1.png', 'images/content2.png', 'images/content3.png');

-- --------------------------------------------------------

--
-- Table structure for table `curriculum`
--

CREATE TABLE `curriculum` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `curriculum`
--

INSERT INTO `curriculum` (`id`, `title`, `content`, `image`) VALUES
(1, ' CHƯƠNG TRÌNH GIÁO DỤC', 'Chương trình tại Mần non 11 là chương trình dựa trên sự tích hợp các cách tiếp cận giáo dục đỉnh cao trong giáo dục đầu đời hiện nay: Thuyết Trí Thông Minh Đa Dạng của Howard Gardner nhấn mạnh rằng cần có chương trình giáo dục hướng tới cá nhân để có thể phát triển những khả năng tiềm ẩn, vốn luôn tồn tại, trong mỗi trẻ; Cách Tiếp cận Dự án (Project approach) của Lilian Katz (Mỹ) tạo cơ hội cho trẻ được trở thành những nhà khoa học ham tìm tòi và khám phá; Phương pháp Montessori nhằm phát triển kỹ năng học tập, kỹ năng sống và 5 giác quan của trẻ; Cách tiếp cận Reggio Emilia xuất phát từ Ý và hiện đang được đánh giá rất cao và áp dụng tại những trường mầm non tốt nhất ở nhiều nước trên thế giới vì mở rộng cánh cửa cho trí tưởng tượng và sáng tạo của trẻ được bay bổng; Lý thuyết Giáo dục Kỹ năng Cảm xúc Xã hội (SEL) hướng tới dạy kỹ năng cảm xúc xã hội (còn gọi là EQ) như là một cách nuôi dưỡng và phát triển trí lực và thông minh cảm xúc của trẻ; Các cách tiếp cận này thể hiện mạnh mẽ nguyên tắc dạy học tích cực và lấy trẻ làm trung tâm, giúp trẻ phát triển tối đa khả năng tư duy độc lập, khả năng giải quyết vấn đề, năng lực sáng tạo cũng như khả năng hợp tác.', 'cth2.png'),
(2, 'PHƯƠNG PHÁP MONTESSORI', 'Lớp học Montessori là một môi trường đặc biệt vừa đảm bảo sự nghiêm túc, kỷ luật vừa đảm bảo sự tự do, hào hứng ở tất cả các trẻ. Môi trường đặc biệt này được xác định bởi hai yếu tố trọng tâm là giáo cụ Montessori và giáo viên đã được đào tạo về phương pháp Montessori. Trong lớp học Montessori, các giáo cụ được giáo viên sắp xếp vào 5 góc hoạt động: góc sinh hoạt (hay góc luyện tập các kĩ năng sinh hoạt), góc cảm giác (hay góc luyện tập giác quan), góc toán, góc ngôn ngữ và góc văn hóa. Kiến thức, kĩ năng của trẻ dần được mở rộng, phát triển hơn khi trẻ làm việc với mỗi giáo cụ trong các góc hoạt động, tạo cơ sở nền tảng giúp trẻ trưởng thành hơn.', 'cth1.png'),
(3, 'CHƯƠNG TRÌNH NGOẠI KHÓA', 'Trường mầm non 11 tổ chức chương trình ngoại khóa đa dạng và phong phú, nhằm giúp trẻ phát triển toàn diện về mặt tư duy, thể chất và kỹ năng xã hội. Chương trình bao gồm nhiều hoạt động đa dạng như thể thao, hội họa, âm nhạc, múa, kể chuyện, nấu ăn và các hoạt động đồng hành khác. Những hoạt động này giúp trẻ rèn luyện và phát triển kỹ năng sáng tạo, tư duy logic, kỹ năng xã hội và rèn luyện sức khỏe, tạo cơ hội cho trẻ học hỏi, khám phá thế giới xung quanh và phát triển khả năng tự lập. Chương trình ngoại khóa của Mầm non 11 cũng giúp trẻ tạo ra những kết nối xã hội, giao lưu và kết bạn mới, giúp trẻ học hỏi và trải nghiệm những giá trị văn hóa, đồng thời giúp trẻ phát triển sự tự tin và khả năng thích ứng với những môi trường mới.', 'cth3.png');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `id` int NOT NULL,
  `eventname` varchar(255) NOT NULL,
  `eventdescription` text,
  `eventdate` datetime DEFAULT CURRENT_TIMESTAMP,
  `ClassID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `eventname`, `eventdescription`, `eventdate`, `ClassID`) VALUES
(1, 'vẽ tranh', 'bé tự sáng tạo tranh phong cảnh', '2025-01-13 08:35:00', 1),
(2, 'bé tập làm bánh trôi', 'bé có thể trải nghiệm làm bánh tại lớp', '2025-01-09 08:35:00', 0),
(3, 'hội chợ tết', 'cho bé vui chơi', '2025-01-13 08:48:00', 0),
(4, 'múa hát chuẩn bị văn nghẹ tết 2024', 'múa hát chuẩn bị văn nghẹ tết 2024', '2025-01-15 09:15:00', 1),
(5, 'bé chơi trò chơi ngời trời', 'bé chơi trò chơi ngời trời', '2025-01-14 09:16:00', 0),
(6, 'học', 'làm quen ngôn ngữ mưới', '2025-01-13 15:53:00', 1),
(7, 'học tiếnh anh', 'làm quen với tiếng anh', '2025-01-13 16:01:00', 1),
(8, 'bé chơi trò chơi sáng tạo', 'bé chơi trò chơi sáng tạo', '2025-01-13 16:10:00', 1),
(9, 'văn nghệ tập thể b2', 'văn nghệ tập thể b2', '2025-01-16 02:28:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `eventphoto`
--

CREATE TABLE `eventphoto` (
  `id` int NOT NULL,
  `eventid` int NOT NULL,
  `photo_name` varchar(255) NOT NULL,
  `photo_description` text,
  `upload_date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eventphoto`
--

INSERT INTO `eventphoto` (`id`, `eventid`, `photo_name`, `photo_description`, `upload_date`) VALUES
(1, 1, 'content1.png', '', '2025-01-14 00:10:19'),
(2, 1, 'content2.png', '', '2025-01-14 00:10:19'),
(3, 1, 'content3.png', '', '2025-01-14 00:10:19'),
(4, 8, 'event-phattriencamxuc.jpg', '', '2025-01-14 00:29:40'),
(5, 8, 'event-sangtao.jpg', '', '2025-01-14 00:29:41'),
(6, 7, 'event-mubaohiem2.jpg', '', '2025-01-14 00:31:18'),
(7, 7, 'event-mubaohiem3.jpg', '', '2025-01-14 00:31:18'),
(8, 7, 'event-mubaohiem4.jpg', '', '2025-01-14 00:31:18'),
(9, 7, 'event-sangtao.jpg', '', '2025-01-14 00:31:18'),
(10, 6, 'event-mam11 - Copy.jpg', '', '2025-01-14 00:32:38'),
(11, 6, 'event-mam11.jpg', '', '2025-01-14 00:32:38'),
(12, 6, 'event-mam111 - Copy.jpg', '', '2025-01-14 00:32:38'),
(13, 4, 'slide1.png', '', '2025-01-14 00:33:33'),
(14, 4, 'slide2.jpg', '', '2025-01-14 00:33:33'),
(15, 4, 'slide3.jpg', '', '2025-01-14 00:33:33'),
(16, 1, 'content3.png', '', '2025-01-14 00:38:28'),
(17, 1, 'banner-tintuc.jpg', '', '2025-01-14 00:39:53'),
(18, 9, 'content3.png', '', '2025-01-14 09:35:28');

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--

CREATE TABLE `grade` (
  `id` int NOT NULL,
  `GradeName` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grade`
--

INSERT INTO `grade` (`id`, `GradeName`) VALUES
(1, '2-3 tuổi'),
(2, '3-4 tuổi'),
(3, '4-5 tuổi');

-- --------------------------------------------------------

--
-- Table structure for table `slides`
--

CREATE TABLE `slides` (
  `id` int NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description_vn` text,
  `description_en` text,
  `author` varchar(255) DEFAULT NULL,
  `button_text` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `slides`
--

INSERT INTO `slides` (`id`, `title`, `description_vn`, `description_en`, `author`, `button_text`, `image_url`) VALUES
(1, 'Sự khởi đầu tươi sáng', '“Trẻ em là những người thầy tuyệt vời, chúng dạy ta về sự yêu thương, lòng kiên nhẫn và niềm vui.”.', '“Children are wonderful teachers, they teach us love, patience, and joy.”', 'Albert Einstein', 'Học từ trẻ em', 'images/slide1.png'),
(2, 'Chắp cánh ước mơ', '“Một đứa trẻ có thể bay cao hơn khi chúng được trao cơ hội và sự tự do để khám phá thế giới.”.', '“A child can soar higher when given the opportunity and freedom to explore the world.”', 'Carl Jung ', 'Khám phá tiềm năng', 'images/slide3.jpg'),
(3, 'Nơi ươm mầm hạnh phúc', '“Điều quan trọng đầu tiên trong sự phát triển của trẻ nhỏ là sự tập trung. Đứa trẻ tập trung sẽ vô cùng vui vẻ.”.', '“The first essential for the child’s development is concentration. The child who concentrates is immensely happy.”', 'Maria Montessori', 'Những điều chia sẻ', 'images/slide2.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int NOT NULL,
  `FullName` varchar(100) NOT NULL,
  `DateOfBirth` date NOT NULL,
  `Gender` varchar(50) NOT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `ParentName` varchar(100) NOT NULL,
  `ParentPhone` varchar(15) DEFAULT NULL,
  `ClassID` int DEFAULT NULL,
  `StudentID` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `FullName`, `DateOfBirth`, `Gender`, `Address`, `ParentName`, `ParentPhone`, `ClassID`, `StudentID`) VALUES
(1, 'Nguyen Thi Hoa', '2015-09-10', 'Female', 'Hanoi, Vietnam', 'Nguyen Tuan', '0987654321', 3, 'MNX000000020'),
(3, 'Le Thi An', '2016-03-15', 'Male', 'Da Nang, Vietnam', 'Le Van Hien', '0967654321', 15, 'MNX000000003'),
(4, 'Pham Dinh Minh Dũng', '2013-12-25', 'Male', 'Hai Phong, Vietnam', 'Pham Van Huy', '09863734', 1, 'MNX000000004'),
(7, 'Dũng', '2005-12-22', 'Male', 'thái nguyên', 'dfghjkl', '3456777', 20, 'MNX000000007'),
(9, 'Phạm Văn Anh', '2020-12-19', 'Male', 'Thái Nguyên, Việt Nam', 'An Thu Trang', '0987654', 1, 'MNX000000009'),
(10, 'Nguyễn Thị Bích', '2020-11-10', 'Nữ', 'Hà Nội, Việt Nam', 'Nguyễn Văn A', '0987655', 1, 'MNX000000010'),
(11, 'Lê Minh Tuấn', '2020-10-05', 'Nam', 'Hồ Chí Minh, Việt Nam', 'Lê Thị Lan', '0987656', 2, 'MNX000000011'),
(12, 'Trần Thiện Mỹ', '2020-09-17', 'Nữ', 'Đà Nẵng, Việt Nam', 'Trần Văn Lâm', '0957654321', 2, 'MNX000000012'),
(14, 'Dương Văn Tuân', '2020-07-15', 'Nam', 'Vũng Tàu, Việt Nam', 'Dương Thị Mai', '0987659', 3, 'MNX000000014'),
(15, 'Vũ Tiến Đạt', '2020-06-22', 'Nam', 'Bắc Giang, Việt Nam', 'Vũ Minh Tú', '0987660', 14, 'MNX000000015'),
(16, 'Ngô Phương Anh', '2020-05-14', 'Nữ', 'Hải Phòng, Việt Nam', 'Ngô Văn Bình', '0987661', 4, 'MNX000000016'),
(17, 'Đỗ Ngọc Nhi', '2020-04-30', 'Nữ', 'Quảng Nam, Việt Nam', 'Đỗ Minh Khang', '0987662', 1, 'MNX000000017'),
(18, 'Bùi Thanh Tâm', '2020-03-25', 'Nam', 'Bình Dương, Việt Nam', 'Bùi Ngọc Lan', '0987663', 10, 'MNX000000018'),
(19, 'AN Hoai Linh', '1111-11-11', 'Nữ', 'Vietnam', '1111', '111', 16, 'MNX000000019'),
(20, 'huy', '2021-11-22', 'Nam', 'Thái bình', 'pham van ha', '0987', 19, 'MNX000000021'),
(21, 'hoàng', '2019-02-21', 'Nam', 'thái nguyên', 'hoang thái', '09877', 7, 'MNX000000022'),
(22, 'hương', '2019-02-22', 'Nữ', 'Thái nguey', 'thuong', '4444', 17, 'MNX000000023'),
(23, 'quang', '2019-11-11', 'Nam', 'Vietnam', 'Nguyen Tuan', '222', 19, 'MNX000000024'),
(24, 'loan', '1111-11-11', 'Nữ', 'Vietnam', 'Nguyen Tuan', '4444', 11, 'MNX000000025'),
(25, 'AN long', '2020-02-01', 'Nam', 'Vietnam', 'pham van ha', '222', 11, 'MNX000000026'),
(26, 'lý thị loan', '2019-11-02', 'Nữ', 'Vietnam', 'Hoang thi anh', '1111', 6, 'MNX000000027'),
(27, 'hoàng thị hoa', '2019-12-01', 'Nữ', 'Vietnam', 'linh', '1111', 7, 'MNX000000028'),
(28, 'Lê Thu Trang', '2019-12-09', 'Nữ', 'Thái nguyên', 'Lê Văn Thắng', '097625182', 1, 'MNX000000029'),
(29, 'An Nhật Anh', '2019-02-10', 'Nam', 'Thái Nguyên', 'An Văn Lê', '08633333', 1, 'MNX000000030'),
(30, 'Nguyễn Khánh Tâm', '2019-12-08', 'Nữ', 'Thái Nguyên', 'Nguyễn Văn Hoa', '098665656', 1, 'MNX000000031'),
(31, 'Hà Văn Hội', '2020-12-19', 'nam', 'Thái nguyên', 'Hà Văn Tiên', '0998765', 1, 'MNX000000032'),
(32, 'Nguyễn Văn A', '2010-05-15', 'nam', 'Hà Nội', 'Nguyễn Văn B', '0901234567', 1, 'MNX000000033'),
(33, 'Trần Thị B', '2012-03-22', 'nữ', 'Hải Phòng', 'Trần Văn C', '0987654321', 2, 'MNX000000034'),
(34, 'Phạm Quốc C', '2011-08-10', 'nam', 'Đà Nẵng', 'Phạm Thị D', '0978888999', 3, 'MNX000000035'),
(35, 'văn huy', '2019-03-03', 'Nam', 'Thái nguyên', 'văn hà', '097538252', 19, 'MNX000000036'),
(36, 'le văn hà', '2020-04-04', 'Nữ', 'Thái nguyên', 'lê thị trang', '09725146271', 10, 'MNX000000037'),
(37, 'Hoàng Lâm', '2021-12-02', 'Nam', 'Thái NguyênTh', 'Hoàng thị Mai', '0976524263', 9, 'MNX000000038'),
(38, 'AN Hoai Linh', '2020-02-04', 'Nữ', 'Thái Nguyên', 'nan an', '098765', 14, 'MNX000000039'),
(39, 'Thái Thị Hoa', '2019-04-04', 'Nữ', 'Thái Nguyên', 'Thái văn hòa', '09876', 16, 'MNX000000040');

--
-- Triggers `students`
--
DELIMITER $$
CREATE TRIGGER `before_insert_students` BEFORE INSERT ON `students` FOR EACH ROW BEGIN
    DECLARE new_id INT;

    -- Lấy giá trị lớn nhất hiện tại trong StudentID và tăng lên 1
    SELECT COALESCE(MAX(CAST(SUBSTRING(StudentID, 4) AS UNSIGNED)), 0) + 1 
    INTO new_id
    FROM students;

    -- Gán giá trị StudentID với định dạng MNX + 9 số
    SET NEW.StudentID = CONCAT('MNX', LPAD(new_id, 9, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int NOT NULL,
  `FullName` varchar(100) NOT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `HireDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `FullName`, `Phone`, `Email`, `Address`, `HireDate`) VALUES
(3, 'Nguyễn Thùy Trang', '0903234567', 'thuytrang@example.com', 'Da Nang, Vietnam', '2015-03-20'),
(4, 'Pham Thi DƯơng', '0904234567', 'phamthid@example.com', 'Hai Phong, Vietnam', '2018-06-30'),
(6, 'Phạm Đinh Minh DŨng', '09861458', 'dung@gmail.com', 'Ha Noi, Viet Nam', '2024-12-09'),
(7, 'Phạm Văn DŨng', '09861458', 'vdung@gmail.com', 'Thanh Hoa, Viet Nam', '2024-10-29'),
(8, 'Nguyễn Thùy Trang', '09861458', 'Trang@gmail.com', 'Bac Giang, Viet Nam', '2024-09-09'),
(9, 'Lê Thị Quỳnh Anh', '09861458', 'tquynh@gmail.com', 'Thai Nguyen, Viet Nam', '2024-12-09'),
(11, 'tuyết mai', '0987654', 'tmai@gmail.com', 'Thái ngueyn', '2024-10-10'),
(12, 'AN Hoai Linh', '0987654', 'anlinh3011@gmail.com', 'Vietnam', '2024-11-06'),
(13, 'lan', '098765', 'lan@gmail.com', 'Thái nguyên', '2024-01-09'),
(14, 'Lan', '0987654321', 'lan1@gmail.com', 'Thái Nguyên', '2024-01-09'),
(15, 'Hòa', '0987654322', 'hoa2@gmail.com', 'Hà Nội', '2024-01-10'),
(16, 'Minh', '0987654323', 'minh3@gmail.com', 'Hồ Chí Minh', '2024-01-11'),
(17, 'Nam', '0987654324', 'nam4@gmail.com', 'Đà Nẵng', '2024-01-12'),
(18, 'Thảo', '0987654325', 'thao5@gmail.com', 'Thái Bình', '2024-01-13'),
(19, 'Hương', '0987654326', 'huong6@gmail.com', 'Nghệ An', '2024-01-14'),
(20, 'Phong', '0987654327', 'phong7@gmail.com', 'Bắc Giang', '2024-01-15'),
(21, 'Quang', '0987654328', 'quang8@gmail.com', 'Lạng Sơn', '2024-01-16'),
(22, 'Trang', '0987654329', 'trang9@gmail.com', 'Hòa Bình', '2024-01-17'),
(23, 'Dung', '090323456711', 'dung10@gmail.com', 'Da1 Nang, Vietnam', '2024-01-18'),
(24, 'Tuấn', '0987654331', 'tuan11@gmail.com', 'Quảng Ninh', '2024-01-19'),
(25, 'Vy', '0987654332', 'vy12@gmail.com', 'Bình Dương', '2024-01-20'),
(26, 'Ngọc', '0987654333', 'ngoc13@gmail.com', 'Thái Nguyên', '2024-01-21'),
(27, 'Khoa', '0987654334', 'khoa14@gmail.com', 'Huế', '2024-01-22'),
(28, 'Thu', '0987654335', 'thu15@gmail.com', 'Cần Thơ', '2024-01-23'),
(29, 'An Nhật Minh', '098765', 'minh@gmail.com', 'Hợp thịnh', '2004-10-10'),
(30, 'An Tuyết Nhi', '098765', 'an@gmail.com', 'Thái Nguyên', '2020-02-03'),
(31, 'thị thúy', '09861458', 'tthuy@gmail.com', 'Bình định', '2023-01-18'),
(32, 'Nguyễn Thị An', '09861234', 'an.nguyen@gmail.com', 'Hà Nội', '2022-06-15'),
(33, 'Trần Văn Bảo', '09867654', 'bao.tran@gmail.com', 'Hải Phòng', '2021-09-20'),
(34, 'Lê Thị Cẩm', '09863289', 'cam.le@gmail.com', 'TP. HCM', '2023-02-12'),
(35, 'Phan Minh Dũng', '09865432', 'dung.phan@gmail.com', 'Đà Nẵng', '2020-11-05'),
(36, 'Nguyễn Thị Diễm', '09878901', 'diem.nguyen@gmail.com', 'Bình Dương', '2023-05-10'),
(37, 'Vũ Hồng Đào', '09872145', 'dao.vu@gmail.com', 'Cần Thơ', '2021-01-09'),
(38, 'Phạm Thị Hoa', '09876543', 'hoa.pham@gmail.com', 'Quảng Ninh', '2020-08-22'),
(39, 'Bùi Duy Kiên', '09874321', 'kien.bui@gmail.com', 'Hà Nam', '2022-10-30'),
(40, 'Trương Thị Lan', '09873210', 'lan.truong@gmail.com', 'Hải Dương', '2023-07-25'),
(41, 'Đặng Minh Lâm', '09865478', 'lam.dang@gmail.com', 'Đồng Nai', '2021-12-18'),
(42, 'Nguyễn Hoàng Nam', '09869321', 'nam.nguyen@gmail.com', 'Kiên Giang', '2022-04-14'),
(43, 'Lê Minh Ngọc', '09863459', 'ngoc.le@gmail.com', 'Phú Thọ', '2021-11-30'),
(44, 'Vũ Hải Ngọc', '09862345', 'ngoc.vu@gmail.com', 'Nghệ An', '2023-03-19'),
(45, 'Trần Thị Quỳnh', '09865490', 'quynh.tran@gmail.com', 'Quảng Bình', '2020-06-25'),
(46, 'Lê Hoàng Quân', '09867890', 'quan.le@gmail.com', 'TP. HCM', '2021-04-17'),
(47, 'Nguyễn Ngọc Sơn', '09871234', 'son.nguyen@gmail.com', 'Bình Phước', '2022-02-08'),
(48, 'Trần Hải Sơn', '09876565', 'son.tran@gmail.com', 'An Giang', '2020-09-19'),
(49, 'Phạm Thị Thu', '09865467', 'thu.pham@gmail.com', 'Long An', '2021-07-12'),
(50, 'Vũ Duy Thắng', '09864589', 'thang.vu@gmail.com', 'Bà Rịa - Vũng Tàu', '2023-04-23'),
(51, 'Nguyễn Tân Thảo', '09868790', 'thao.nguyen@gmail.com', 'Cà Mau', '2020-11-11'),
(52, 'Trần Thị Thúy', '09861845', 'thuy.tran@gmail.com', 'Bình Thuận', '2021-03-17'),
(53, 'Bùi Minh Tâm', '09863217', 'tam.bui@gmail.com', 'Hà Nội', '2022-05-13'),
(54, 'Đỗ Thị Thi', '09867656', 'thi.do@gmail.com', 'Thừa Thiên Huế', '2020-10-01'),
(55, 'Lê Hoàng Tuấn', '09864567', 'tuan.le@gmail.com', 'Lâm Đồng', '2023-06-08'),
(56, 'Nguyễn Quang Vinh', '09878765', 'vinh.nguyen@gmail.com', 'Quảng Trị', '2021-08-23'),
(57, 'Trương Thị Vân', '09873287', 'van.truong@gmail.com', 'Bắc Giang', '2022-07-30'),
(58, 'Phan Thị Việt', '09864532', 'viet.phan@gmail.com', 'Vĩnh Long', '2020-12-09'),
(59, 'Vũ Minh Đạt', '09865434', 'dat.vu@gmail.com', 'Ninh Bình', '2021-05-18'),
(60, 'Lê Đoàn Thị', '09861287', 'thi.doan@gmail.com', 'Quảng Ngãi', '2023-02-24'),
(61, 'Trần Ngọc Đan', '09867345', 'dan.tran@gmail.com', 'Nam Định', '2022-08-10'),
(62, 'Nguyễn Hương Lan', '09865423', 'lan.nguyen@gmail.com', 'Bắc Ninh', '2020-04-30'),
(63, 'Nguyễn Anh Tuấn', '0987654321', 'tuannguyen@gmail.com', 'Hà Nội', '2023-02-15'),
(64, 'Lê Thanh Hải', '0976532123', 'lethanhhai@gmail.com', 'Hồ Chí Minh', '2023-03-20'),
(65, 'Trần Minh Tuấn', '0912345678', 'tranminhtuan@gmail.com', 'Đà Nẵng', '2023-04-10'),
(66, 'Phạm Thiết Nhân', '0908765432', 'phamthietnhan@gmail.com', 'Cần Thơ', '2023-05-05'),
(67, 'Đoàn Ngọc Lan', '0945678910', 'doanngoclan@gmail.com', 'Bình Dương', '2023-06-08'),
(68, 'Hoàng Anh Khoa', '0987123456', 'hoanganhkhoa@gmail.com', 'Vĩnh Long', '2023-07-12'),
(69, 'Nguyễn Thị Minh', '0965456789', 'nguyenminh@gmail.com', 'Hà Nội', '2023-08-20'),
(70, 'Trần Thị Lan', '0934123456', 'tranlan@gmail.com', 'Hồ Chí Minh', '2023-09-01'),
(71, 'Lê Thanh Tùng', '0906543210', 'lethanhtung@gmail.com', 'Đà Nẵng', '2023-10-05'),
(72, 'Nguyễn Thị Mai', '0943123456', 'nguyenmaivn@gmail.com', 'Cần Thơ', '2023-11-02'),
(73, 'Trương Ngọc Minh', '0914456789', 'truongngocminh@gmail.com', 'Bình Dương', '2023-12-01'),
(74, 'Phan Quang Vinh', '0935667890', 'phanquangvinh@gmail.com', 'Hồ Chí Minh', '2024-01-14'),
(75, 'Trương Quang Duy', '0967889999', 'truongquangduy@gmail.com', 'Vĩnh Long', '2024-02-12'),
(76, 'Đặng Hoài Nam', '0901234567', 'danghoainam@gmail.com', 'Hà Nội', '2024-03-10'),
(77, 'Nguyễn Văn Tấn', '0978234567', 'nguyentantan@gmail.com', 'Bình Dương', '2024-04-05'),
(78, 'Lê Hoàng Thảo', '0987654321', 'lehoangthao@gmail.com', 'Đà Nẵng', '2024-05-19'),
(79, 'Trần Thái Hà', '0912345678', 'tranthainguyen@gmail.com', 'Hồ Chí Minh', '2024-06-01'),
(80, 'Phạm Minh Hiếu', '0932123456', 'phamminhhieu@gmail.com', 'Cần Thơ', '2024-07-14'),
(81, 'Nguyễn Phan Khải', '0906545678', 'nguyenphankhai@gmail.com', 'Vĩnh Long', '2024-08-20');

-- --------------------------------------------------------

--
-- Table structure for table `time`
--

CREATE TABLE `time` (
  `id` int NOT NULL,
  `timename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `time`
--

INSERT INTO `time` (`id`, `timename`) VALUES
(1, '7:30 - 8:00'),
(2, '8:00 - 8:15'),
(3, '8:15 - 9:00'),
(4, '9:00 - 10:00'),
(5, '10:00 - 10:15'),
(6, '10:15 - 11:00'),
(7, '11:00 - 11:30'),
(8, '11:30 - 13:30'),
(9, '13:30 - 14:00'),
(10, '14:00 - 15:00'),
(11, '15:00 - 15:30'),
(12, '15:30 - 16:00'),
(13, '16:00 - 16:30');

-- --------------------------------------------------------

--
-- Table structure for table `timetable`
--

CREATE TABLE `timetable` (
  `id` int NOT NULL,
  `GradeID` int DEFAULT NULL,
  `ActivityID` int DEFAULT NULL,
  `TimeID` int DEFAULT NULL,
  `Description` varchar(500) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timetable`
--

INSERT INTO `timetable` (`id`, `GradeID`, `ActivityID`, `TimeID`, `Description`, `date`) VALUES
(185, 1, 1, 1, 'web2', '2025-01-13'),
(186, 2, 1, 1, 'hia', '2025-01-12'),
(187, 2, 2, 2, 'aaa', '2025-01-12'),
(188, 2, 3, 3, 'aaa', '2025-01-12'),
(189, 2, 4, 4, 'b', '2025-01-12'),
(190, 2, 5, 5, 'web2', '2025-01-12'),
(191, 2, 6, 6, 'web2', '2025-01-12'),
(192, 2, 7, 7, '', '2025-01-12'),
(193, 2, 8, 8, 'aaaaaaaaaaaaaaaa', '2025-01-12'),
(194, 2, 9, 9, '', '2025-01-12'),
(195, 2, 10, 10, 'z', '2025-01-12'),
(196, 2, 11, 11, 'chơi ngoài trời', '2025-01-12'),
(197, 2, 12, 12, '', '2025-01-12'),
(198, 2, 13, 13, '', '2025-01-12'),
(199, 1, 1, 1, '', '2025-01-13'),
(200, 1, 2, 2, '', '2025-01-13'),
(201, 1, 3, 3, '', '2025-01-13'),
(202, 1, 4, 4, '', '2025-01-13'),
(203, 1, 5, 5, '', '2025-01-13'),
(204, 1, 6, 6, '', '2025-01-13'),
(205, 1, 7, 7, '', '2025-01-13'),
(206, 1, 8, 8, '', '2025-01-13'),
(207, 1, 9, 9, '', '2025-01-13'),
(208, 1, 10, 10, '', '2025-01-13'),
(209, 1, 11, 11, '', '2025-01-13'),
(210, 1, 12, 12, '', '2025-01-13'),
(211, 1, 13, 13, '', '2025-01-13'),
(212, 3, 1, 1, 'đón trẻ', '2025-01-12'),
(213, 3, 2, 2, '', '2025-01-12'),
(214, 3, 3, 3, '', '2025-01-12'),
(215, 3, 4, 4, '', '2025-01-12'),
(216, 3, 5, 5, '', '2025-01-12'),
(217, 3, 6, 6, '', '2025-01-12'),
(218, 3, 7, 7, '', '2025-01-12'),
(219, 3, 8, 8, '', '2025-01-12'),
(220, 3, 9, 9, '', '2025-01-12'),
(221, 3, 10, 10, '', '2025-01-12'),
(222, 3, 11, 11, '', '2025-01-12'),
(223, 3, 12, 12, '', '2025-01-12'),
(224, 3, 13, 13, '', '2025-01-12'),
(225, 1, 1, 1, '', '2025-01-14'),
(226, 1, 2, 2, '', '2025-01-14'),
(227, 1, 3, 3, '', '2025-01-14'),
(228, 1, 4, 4, '', '2025-01-14'),
(229, 1, 5, 5, '', '2025-01-14'),
(230, 1, 6, 6, '', '2025-01-14'),
(231, 1, 7, 7, '', '2025-01-14'),
(232, 1, 8, 8, '', '2025-01-14'),
(233, 1, 9, 9, '', '2025-01-14'),
(234, 1, 10, 10, '', '2025-01-14'),
(235, 1, 11, 11, '', '2025-01-14'),
(236, 1, 12, 12, '', '2025-01-14'),
(237, 1, 13, 13, '', '2025-01-14'),
(238, 2, 1, 1, '', '2025-01-14'),
(239, 2, 2, 2, '', '2025-01-14'),
(240, 2, 3, 3, '', '2025-01-14'),
(241, 2, 4, 4, '', '2025-01-14'),
(242, 2, 5, 5, '', '2025-01-14'),
(243, 2, 6, 6, '', '2025-01-14'),
(244, 2, 7, 7, '', '2025-01-14'),
(245, 2, 8, 8, '', '2025-01-14'),
(246, 2, 9, 9, '', '2025-01-14'),
(247, 2, 10, 10, '', '2025-01-14'),
(248, 2, 11, 11, '', '2025-01-14'),
(249, 2, 12, 12, '', '2025-01-14'),
(250, 2, 13, 13, '', '2025-01-14'),
(251, 1, 1, 1, '', '2025-01-15'),
(252, 1, 2, 2, '', '2025-01-15'),
(253, 1, 3, 3, '', '2025-01-15'),
(254, 1, 4, 4, '', '2025-01-15'),
(255, 1, 5, 5, '', '2025-01-15'),
(256, 1, 6, 6, '', '2025-01-15'),
(257, 1, 7, 7, '', '2025-01-15'),
(258, 1, 8, 8, '', '2025-01-15'),
(259, 1, 9, 9, '', '2025-01-15'),
(260, 1, 10, 10, '', '2025-01-15'),
(261, 1, 11, 11, '', '2025-01-15'),
(262, 1, 12, 12, '', '2025-01-15'),
(263, 1, 13, 13, '', '2025-01-15'),
(264, 2, 1, 1, '', '2025-01-07'),
(265, 2, 2, 2, '', '2025-01-07'),
(266, 2, 3, 3, '', '2025-01-07'),
(267, 2, 4, 4, '', '2025-01-07'),
(268, 2, 5, 5, '', '2025-01-07'),
(269, 2, 6, 6, '', '2025-01-07'),
(270, 2, 7, 7, '', '2025-01-07'),
(271, 2, 8, 8, '', '2025-01-07'),
(272, 2, 9, 9, '', '2025-01-07'),
(273, 2, 10, 10, '', '2025-01-07'),
(274, 2, 11, 11, '', '2025-01-07'),
(275, 2, 12, 12, '', '2025-01-07'),
(276, 2, 13, 13, '', '2025-01-07'),
(277, 2, 1, 1, 'aa', '2025-01-15'),
(278, 2, 2, 2, 'aa', '2025-01-15'),
(279, 2, 3, 3, 'aa', '2025-01-15'),
(280, 2, 4, 4, 'aa', '2025-01-15'),
(281, 2, 5, 5, 'aa', '2025-01-15'),
(282, 2, 6, 6, '', '2025-01-15'),
(283, 2, 7, 7, '', '2025-01-15'),
(284, 2, 8, 8, '', '2025-01-15'),
(285, 2, 9, 9, '', '2025-01-15'),
(286, 2, 10, 10, '', '2025-01-15'),
(287, 2, 11, 11, '', '2025-01-15'),
(288, 2, 12, 12, '', '2025-01-15'),
(289, 2, 13, 13, '', '2025-01-15'),
(290, 1, 1, 1, '', '2025-01-15'),
(291, 1, 2, 2, '', '2025-01-15'),
(292, 1, 3, 3, '', '2025-01-15'),
(293, 1, 4, 4, '', '2025-01-15'),
(294, 1, 5, 5, '', '2025-01-15'),
(295, 1, 6, 6, '', '2025-01-15'),
(296, 1, 7, 7, '', '2025-01-15'),
(297, 1, 8, 8, '', '2025-01-15'),
(298, 1, 9, 9, '', '2025-01-15'),
(299, 1, 10, 10, '', '2025-01-15'),
(300, 1, 11, 11, '', '2025-01-15'),
(301, 1, 12, 12, '', '2025-01-15'),
(302, 1, 13, 13, '', '2025-01-15'),
(303, 1, 1, 1, '', '2025-01-16'),
(304, 1, 2, 2, '', '2025-01-16'),
(305, 1, 3, 3, '', '2025-01-16'),
(306, 1, 4, 4, '', '2025-01-16'),
(307, 1, 5, 5, '', '2025-01-16'),
(308, 1, 6, 6, '', '2025-01-16'),
(309, 1, 7, 7, '', '2025-01-16'),
(310, 1, 8, 8, '', '2025-01-16'),
(311, 1, 9, 9, '', '2025-01-16'),
(312, 1, 10, 10, '', '2025-01-16'),
(313, 1, 11, 11, '', '2025-01-16'),
(314, 1, 12, 12, '', '2025-01-16'),
(315, 1, 13, 13, '', '2025-01-16'),
(316, 1, 1, 1, '', '2025-01-16'),
(317, 1, 2, 2, '', '2025-01-16'),
(318, 1, 3, 3, '', '2025-01-16'),
(319, 1, 4, 4, '', '2025-01-16'),
(320, 1, 5, 5, '', '2025-01-16'),
(321, 1, 6, 6, '', '2025-01-16'),
(322, 1, 7, 7, '', '2025-01-16'),
(323, 1, 8, 8, '', '2025-01-16'),
(324, 1, 9, 9, '', '2025-01-16'),
(325, 1, 10, 10, '', '2025-01-16'),
(326, 1, 11, 11, '', '2025-01-16'),
(327, 1, 12, 12, '', '2025-01-16'),
(328, 1, 13, 13, '', '2025-01-16'),
(329, 1, 1, 1, '', '2025-01-16'),
(330, 1, 2, 2, '', '2025-01-16'),
(331, 1, 3, 3, '', '2025-01-16'),
(332, 1, 4, 4, '', '2025-01-16'),
(333, 1, 5, 5, '', '2025-01-16'),
(334, 1, 6, 6, '', '2025-01-16'),
(335, 1, 7, 7, '', '2025-01-16'),
(336, 1, 8, 8, '', '2025-01-16'),
(337, 1, 9, 9, '', '2025-01-16'),
(338, 1, 10, 10, '', '2025-01-16'),
(339, 1, 11, 11, '', '2025-01-16'),
(340, 1, 12, 12, '', '2025-01-16'),
(341, 1, 13, 13, '', '2025-01-16'),
(342, 3, 1, 1, '', '2025-01-16'),
(343, 3, 2, 2, '', '2025-01-16'),
(344, 3, 3, 3, '', '2025-01-16'),
(345, 3, 4, 4, '', '2025-01-16'),
(346, 3, 5, 5, '', '2025-01-16'),
(347, 3, 6, 6, '', '2025-01-16'),
(348, 3, 7, 7, '', '2025-01-16'),
(349, 3, 8, 8, '', '2025-01-16'),
(350, 3, 9, 9, '', '2025-01-16'),
(351, 3, 10, 10, '', '2025-01-16'),
(352, 3, 11, 11, '', '2025-01-16'),
(353, 3, 12, 12, '', '2025-01-16'),
(354, 3, 13, 13, '', '2025-01-16'),
(355, 3, 1, 1, '', '2025-01-16'),
(356, 3, 2, 2, '', '2025-01-16'),
(357, 3, 3, 3, '', '2025-01-16'),
(358, 3, 4, 4, '', '2025-01-16'),
(359, 3, 5, 5, '', '2025-01-16'),
(360, 3, 6, 6, '', '2025-01-16'),
(361, 3, 7, 7, '', '2025-01-16'),
(362, 3, 8, 8, '', '2025-01-16'),
(363, 3, 9, 9, '', '2025-01-16'),
(364, 3, 10, 10, '', '2025-01-16'),
(365, 3, 11, 11, '', '2025-01-16'),
(366, 3, 12, 12, '', '2025-01-16'),
(367, 3, 13, 13, '', '2025-01-16'),
(368, 1, 1, 1, '', '2025-01-17'),
(369, 1, 2, 2, '', '2025-01-17'),
(370, 1, 3, 3, '', '2025-01-17'),
(371, 1, 4, 4, 'vex tranhhhh', '2025-01-17'),
(372, 1, 5, 5, '', '2025-01-17'),
(373, 1, 6, 6, '', '2025-01-17'),
(374, 1, 7, 7, '', '2025-01-17'),
(375, 1, 8, 8, '', '2025-01-17'),
(376, 1, 9, 9, '', '2025-01-17'),
(377, 1, 10, 10, '', '2025-01-17'),
(378, 1, 11, 11, '', '2025-01-17'),
(379, 1, 12, 12, '', '2025-01-17'),
(380, 1, 13, 13, '', '2025-01-17'),
(381, 2, 1, 1, '', '2025-01-13'),
(382, 2, 2, 2, '', '2025-01-13'),
(383, 2, 3, 3, '', '2025-01-13'),
(384, 2, 4, 4, 'haotj động ngoài trời', '2025-01-13'),
(385, 2, 5, 5, 'uống sữa', '2025-01-13'),
(386, 2, 6, 6, '', '2025-01-13'),
(387, 2, 7, 7, '', '2025-01-13'),
(388, 2, 8, 8, '', '2025-01-13'),
(389, 2, 9, 9, '', '2025-01-13'),
(390, 2, 10, 10, '', '2025-01-13'),
(391, 2, 11, 11, '', '2025-01-13'),
(392, 2, 12, 12, '', '2025-01-13'),
(393, 2, 13, 13, '', '2025-01-13'),
(394, 3, 1, 1, 'ddon ter', '2025-01-13'),
(395, 3, 2, 2, '', '2025-01-13'),
(396, 3, 3, 3, '', '2025-01-13'),
(397, 3, 4, 4, '', '2025-01-13'),
(398, 3, 5, 5, '', '2025-01-13'),
(399, 3, 6, 6, '', '2025-01-13'),
(400, 3, 7, 7, '', '2025-01-13'),
(401, 3, 8, 8, '', '2025-01-13'),
(402, 3, 9, 9, '', '2025-01-13'),
(403, 3, 10, 10, '', '2025-01-13'),
(404, 3, 11, 11, '', '2025-01-13'),
(405, 3, 12, 12, '', '2025-01-13'),
(406, 3, 13, 13, '', '2025-01-13'),
(407, 1, 1, 1, '', '2025-01-13'),
(408, 1, 2, 2, '', '2025-01-13'),
(409, 1, 3, 3, '', '2025-01-13'),
(410, 1, 4, 4, '', '2025-01-13'),
(411, 1, 5, 5, '', '2025-01-13'),
(412, 1, 6, 6, '', '2025-01-13'),
(413, 1, 7, 7, '', '2025-01-13'),
(414, 1, 8, 8, '', '2025-01-13'),
(415, 1, 9, 9, '', '2025-01-13'),
(416, 1, 10, 10, '', '2025-01-13'),
(417, 1, 11, 11, '', '2025-01-13'),
(418, 1, 12, 12, '', '2025-01-13'),
(419, 1, 13, 13, '', '2025-01-13'),
(420, 1, 1, 1, '', '2025-01-22'),
(421, 1, 2, 2, '', '2025-01-22'),
(422, 1, 3, 3, '', '2025-01-22'),
(423, 1, 4, 4, '', '2025-01-22'),
(424, 1, 5, 5, '', '2025-01-22'),
(425, 1, 6, 6, '', '2025-01-22'),
(426, 1, 7, 7, '', '2025-01-22'),
(427, 1, 8, 8, '', '2025-01-22'),
(428, 1, 9, 9, '', '2025-01-22'),
(429, 1, 10, 10, '', '2025-01-22'),
(430, 1, 11, 11, '', '2025-01-22'),
(431, 1, 12, 12, '', '2025-01-22'),
(432, 1, 13, 13, '', '2025-01-22'),
(433, 1, 1, 1, '', '2025-01-13'),
(434, 1, 2, 2, '', '2025-01-13'),
(435, 1, 3, 3, '', '2025-01-13'),
(436, 1, 4, 4, '', '2025-01-13'),
(437, 1, 5, 5, '', '2025-01-13'),
(438, 1, 6, 6, '', '2025-01-13'),
(439, 1, 7, 7, '', '2025-01-13'),
(440, 1, 8, 8, '', '2025-01-13'),
(441, 1, 9, 9, '', '2025-01-13'),
(442, 1, 10, 10, '', '2025-01-13'),
(443, 1, 11, 11, '', '2025-01-13'),
(444, 1, 12, 12, '', '2025-01-13'),
(445, 1, 13, 13, '', '2025-01-13'),
(446, 1, 1, 1, 'q', '2025-01-23'),
(447, 1, 2, 2, 'q', '2025-01-23'),
(448, 1, 3, 3, 'q', '2025-01-23'),
(449, 1, 4, 4, 'q', '2025-01-23'),
(450, 1, 5, 5, 'q', '2025-01-23'),
(451, 1, 6, 6, 'q', '2025-01-23'),
(452, 1, 7, 7, 'q', '2025-01-23'),
(453, 1, 8, 8, 'q', '2025-01-23'),
(454, 1, 9, 9, 'q', '2025-01-23'),
(455, 1, 10, 10, 'q', '2025-01-23'),
(456, 1, 11, 11, 'q', '2025-01-23'),
(457, 1, 12, 12, 'q', '2025-01-23'),
(458, 1, 13, 13, 'q', '2025-01-23'),
(459, 2, 1, 1, 'w', '2025-01-22'),
(460, 2, 2, 2, 'w', '2025-01-22'),
(461, 2, 3, 3, 'w', '2025-01-22'),
(462, 2, 4, 4, 'w', '2025-01-22'),
(463, 2, 5, 5, 'w', '2025-01-22'),
(464, 2, 6, 6, '', '2025-01-22'),
(465, 2, 7, 7, '', '2025-01-22'),
(466, 2, 8, 8, '', '2025-01-22'),
(467, 2, 9, 9, '', '2025-01-22'),
(468, 2, 10, 10, '', '2025-01-22'),
(469, 2, 11, 11, '', '2025-01-22'),
(470, 2, 12, 12, '', '2025-01-22'),
(471, 2, 13, 13, '', '2025-01-22'),
(472, 2, 1, 1, '', '2025-01-20'),
(473, 2, 2, 2, '', '2025-01-20'),
(474, 2, 3, 3, '', '2025-01-20'),
(475, 2, 4, 4, '', '2025-01-20'),
(476, 2, 5, 5, '', '2025-01-20'),
(477, 2, 6, 6, '', '2025-01-20'),
(478, 2, 7, 7, '', '2025-01-20'),
(479, 2, 8, 8, '', '2025-01-20'),
(480, 2, 9, 9, '', '2025-01-20'),
(481, 2, 10, 10, '', '2025-01-20'),
(482, 2, 11, 11, '', '2025-01-20'),
(483, 2, 12, 12, '', '2025-01-20'),
(484, 2, 13, 13, '', '2025-01-20'),
(485, 3, 1, 1, 'z', '2025-01-15'),
(486, 3, 2, 2, 'z', '2025-01-15'),
(487, 3, 3, 3, 'z', '2025-01-15'),
(488, 3, 4, 4, 'z', '2025-01-15'),
(489, 3, 5, 5, 'z', '2025-01-15'),
(490, 3, 6, 6, 'z', '2025-01-15'),
(491, 3, 7, 7, '', '2025-01-15'),
(492, 3, 8, 8, '', '2025-01-15'),
(493, 3, 9, 9, '', '2025-01-15'),
(494, 3, 10, 10, '', '2025-01-15'),
(495, 3, 11, 11, '', '2025-01-15'),
(496, 3, 12, 12, '', '2025-01-15'),
(497, 3, 13, 13, '', '2025-01-15'),
(498, 2, 1, 1, '', '2025-01-17'),
(499, 2, 2, 2, 'Tập ngoài sân', '2025-01-17'),
(500, 2, 3, 3, 'bài thơ aa', '2025-01-17'),
(501, 2, 4, 4, 'vẽ tranh con vật', '2025-01-17'),
(502, 2, 5, 5, 'uống sữa', '2025-01-17'),
(503, 2, 6, 6, '', '2025-01-17'),
(504, 2, 7, 7, '', '2025-01-17'),
(505, 2, 8, 8, '', '2025-01-17'),
(506, 2, 9, 9, '', '2025-01-17'),
(507, 2, 10, 10, '', '2025-01-17'),
(508, 2, 11, 11, 'chơi đồ chơi', '2025-01-17'),
(509, 2, 12, 12, 'hát ', '2025-01-17'),
(510, 2, 13, 13, '', '2025-01-17'),
(511, 2, 1, 1, '', '2025-01-16'),
(512, 2, 2, 2, 'tập bài thể dục ngoài trời', '2025-01-16'),
(513, 2, 3, 3, 'học bài thư bé đi học', '2025-01-16'),
(514, 2, 4, 4, 'vẽ tranh', '2025-01-16'),
(515, 2, 5, 5, 'uống sữa', '2025-01-16'),
(516, 2, 6, 6, '', '2025-01-16'),
(517, 2, 7, 7, '', '2025-01-16'),
(518, 2, 8, 8, '', '2025-01-16'),
(519, 2, 9, 9, '', '2025-01-16'),
(520, 2, 10, 10, '', '2025-01-16'),
(521, 2, 11, 11, 'chơi đồ chơi trong lớp', '2025-01-16'),
(522, 2, 12, 12, 'hát ', '2025-01-16'),
(523, 2, 13, 13, '', '2025-01-16'),
(524, 2, 1, 1, '', '2025-01-18'),
(525, 2, 2, 2, '', '2025-01-18'),
(526, 2, 3, 3, '', '2025-01-18'),
(527, 2, 4, 4, '', '2025-01-18'),
(528, 2, 5, 5, '', '2025-01-18'),
(529, 2, 6, 6, '', '2025-01-18'),
(530, 2, 7, 7, '', '2025-01-18'),
(531, 2, 8, 8, '', '2025-01-18'),
(532, 2, 9, 9, '', '2025-01-18'),
(533, 2, 10, 10, '', '2025-01-18'),
(534, 2, 11, 11, '', '2025-01-18'),
(535, 2, 12, 12, '', '2025-01-18'),
(536, 2, 13, 13, '', '2025-01-18'),
(537, 1, 1, 1, '', '2025-02-13'),
(538, 1, 2, 2, '', '2025-02-13'),
(539, 1, 3, 3, 'học hát cánh diều', '2025-02-13'),
(540, 1, 4, 4, 'vẽ trang con vật', '2025-02-13'),
(541, 1, 5, 5, 'sữa chưa', '2025-02-13'),
(542, 1, 6, 6, '', '2025-02-13'),
(543, 1, 7, 7, '', '2025-02-13'),
(544, 1, 8, 8, '', '2025-02-13'),
(545, 1, 9, 9, '', '2025-02-13'),
(546, 1, 10, 10, '', '2025-02-13'),
(547, 1, 11, 11, 'trò chơi', '2025-02-13'),
(548, 1, 12, 12, '', '2025-02-13'),
(549, 1, 13, 13, '', '2025-02-13'),
(550, 2, 1, 1, '', '2025-02-13'),
(551, 2, 2, 2, '', '2025-02-13'),
(552, 2, 3, 3, '', '2025-02-13'),
(553, 2, 4, 4, '', '2025-02-13'),
(554, 2, 5, 5, 'cháo', '2025-02-13'),
(555, 2, 6, 6, '', '2025-02-13'),
(556, 2, 7, 7, '', '2025-02-13'),
(557, 2, 8, 8, '', '2025-02-13'),
(558, 2, 9, 9, '', '2025-02-13'),
(559, 2, 10, 10, '', '2025-02-13'),
(560, 2, 11, 11, '', '2025-02-13'),
(561, 2, 12, 12, '', '2025-02-13'),
(562, 2, 13, 13, '', '2025-02-13'),
(563, 1, 1, 1, '', '2025-02-14'),
(564, 1, 2, 2, 'ngoài sân', '2025-02-14'),
(565, 1, 3, 3, 'học hát cánh diều', '2025-02-14'),
(566, 1, 4, 4, 'múa tập thể', '2025-02-14'),
(567, 1, 5, 5, 'sữa', '2025-02-14'),
(568, 1, 6, 6, '', '2025-02-14'),
(569, 1, 7, 7, '', '2025-02-14'),
(570, 1, 8, 8, '', '2025-02-14'),
(571, 1, 9, 9, '', '2025-02-14'),
(572, 1, 10, 10, '', '2025-02-14'),
(573, 1, 11, 11, '', '2025-02-14'),
(574, 1, 12, 12, '', '2025-02-14'),
(575, 1, 13, 13, '', '2025-02-14'),
(576, 2, 1, 1, '', '2025-02-14'),
(577, 2, 2, 2, '', '2025-02-14'),
(578, 2, 3, 3, 'học thơ chú ếch con', '2025-02-14'),
(579, 2, 4, 4, 'hát múa ', '2025-02-14'),
(580, 2, 5, 5, 'sữaaa', '2025-02-14'),
(581, 2, 6, 6, '', '2025-02-14'),
(582, 2, 7, 7, '', '2025-02-14'),
(583, 2, 8, 8, '', '2025-02-14'),
(584, 2, 9, 9, '', '2025-02-14'),
(585, 2, 10, 10, '', '2025-02-14'),
(586, 2, 11, 11, 'chơi ngoài trời', '2025-02-14'),
(587, 2, 12, 12, '', '2025-02-14'),
(588, 2, 13, 13, '', '2025-02-14'),
(589, 3, 1, 1, '', '2025-02-14'),
(590, 3, 2, 2, '', '2025-02-14'),
(591, 3, 3, 3, 'học thơ', '2025-02-14'),
(592, 3, 4, 4, 'vẽ', '2025-02-14'),
(593, 3, 5, 5, '', '2025-02-14'),
(594, 3, 6, 6, '', '2025-02-14'),
(595, 3, 7, 7, '', '2025-02-14'),
(596, 3, 8, 8, '', '2025-02-14'),
(597, 3, 9, 9, '', '2025-02-14'),
(598, 3, 10, 10, '', '2025-02-14'),
(599, 3, 11, 11, '', '2025-02-14'),
(600, 3, 12, 12, '', '2025-02-14'),
(601, 3, 13, 13, '', '2025-02-14'),
(602, 2, 1, 1, '', '2025-02-15'),
(603, 2, 2, 2, 'hoạt động ngoài trời', '2025-02-15'),
(604, 2, 3, 3, 'học thơ về bác hồ', '2025-02-15'),
(605, 2, 4, 4, 'múa tập thể', '2025-02-15'),
(606, 2, 5, 5, 'chè', '2025-02-15'),
(607, 2, 6, 6, '', '2025-02-15'),
(608, 2, 7, 7, '', '2025-02-15'),
(609, 2, 8, 8, '', '2025-02-15'),
(610, 2, 9, 9, '', '2025-02-15'),
(611, 2, 10, 10, '', '2025-02-15'),
(612, 2, 11, 11, '', '2025-02-15'),
(613, 2, 12, 12, '', '2025-02-15'),
(614, 2, 13, 13, '', '2025-02-15'),
(615, 1, 1, 1, '', '2025-02-15'),
(616, 1, 2, 2, '', '2025-02-15'),
(617, 1, 3, 3, '', '2025-02-15'),
(618, 1, 4, 4, 'vẽ tranh cây cối', '2025-02-15'),
(619, 1, 5, 5, '', '2025-02-15'),
(620, 1, 6, 6, '', '2025-02-15'),
(621, 1, 7, 7, '', '2025-02-15'),
(622, 1, 8, 8, '', '2025-02-15'),
(623, 1, 9, 9, '', '2025-02-15'),
(624, 1, 10, 10, '', '2025-02-15'),
(625, 1, 11, 11, '', '2025-02-15'),
(626, 1, 12, 12, '', '2025-02-15'),
(627, 1, 13, 13, '', '2025-02-15'),
(628, 3, 1, 1, '', '2025-02-15'),
(629, 3, 2, 2, '', '2025-02-15'),
(630, 3, 3, 3, '', '2025-02-15'),
(631, 3, 4, 4, '', '2025-02-15'),
(632, 3, 5, 5, '', '2025-02-15'),
(633, 3, 6, 6, '', '2025-02-15'),
(634, 3, 7, 7, '', '2025-02-15'),
(635, 3, 8, 8, '', '2025-02-15'),
(636, 3, 9, 9, '', '2025-02-15'),
(637, 3, 10, 10, '', '2025-02-15'),
(638, 3, 11, 11, '', '2025-02-15'),
(639, 3, 12, 12, '', '2025-02-15'),
(640, 3, 13, 13, '', '2025-02-15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `studentID` int DEFAULT NULL,
  `teacherID` int DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `DateCreated` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `studentID`, `teacherID`, `email`, `DateCreated`) VALUES
(12, 'minhdung', '$2y$10$7WCLHz85Zi09O8gMFXzLR.p/Fth1lk70JVbODdBQdHBON6xLoKtfe', '3', 4, NULL, 'minhdung@gmail.com', '2025-01-05 15:29:10'),
(13, 'thiduong', '$2y$10$haGzu3qENdIArzQQ9Yb7Q.YZAY1Hy1dAeSYSh1Gb/GvjZOLUM4fh6', '3', 1, NULL, 'thiduong@gmail.com', '2025-01-05 15:29:10'),
(20, 'admin', '$2y$10$xCYzPS6FNdpCVyfq/5JV0uOz66LRI339TtcoJwn2YKD4aJgXiRgye', '1', NULL, NULL, 'admin1@gmail.com', '2025-01-05 15:29:10'),
(21, 'admin2', '$2y$10$QZGWOAqjO2uEMWTq8eC6YuWtgDIig5VR9XeAABDCYW4kEwnkZgzki', '1', NULL, NULL, '', '2025-01-05 15:29:10'),
(22, 'admin3', '$2y$10$7OiZKFKdEt5JHJ22I2Yfhu1707CXQj99BTGsvFuPfM7hJQ64EF9Oq', '1', NULL, NULL, '', '2025-01-05 15:29:10'),
(23, 'thuytrang', '$2y$10$e2ItAKjjNpOJw5qObiBaJuVMwFX2nsmzn2qHoEe/r7gJJ2uprMgbG', '2', NULL, 3, 'thuytrang11@gmail.com', '2025-01-05 15:29:10'),
(31, 'quynhchi', '$2y$10$mKXzDw4YyLwYLYy3MoaC.OrnNyStP6eW/8gBVIjk3uGfbmilnlKXm', '3', 15, NULL, 'an@gmail.com', '2025-01-05 15:30:42'),
(33, 'phuongan', '$2y$10$1cyp1E9oe5b2ACTerNihh.zOZTtfNHq/SOCDXbYpxyba8FPug/ipS', '1', NULL, NULL, 'an@gmail.com', '2025-01-05 23:26:34'),
(38, 'hoailinh', '$2y$10$9lHc9yjew293A3kT/OQJTuEMYrkw4blhzqFJH6wqojQEWSSCmEjX2', '3', 12, NULL, 'an@gmail.com', '2025-01-05 23:34:34'),
(41, 'anhoailinhan', '$2y$10$vfbcIT.b4R1UaXpEKuMgwu1UGolhjrOGCuk4zONCF8EFsA44CMWZy', '2', NULL, 7, 'anlinh3011@gmail.com', '2025-01-05 23:58:11'),
(42, 'linhann1', '$2y$10$7azkW.APytvsRJkwgXme2Om.WXhzXFhJ30VPfqqCJzQZ98aRDbcQ.', '3', 14, NULL, 'tuannn@gmail.com', '2025-01-06 00:07:54'),
(43, 'quynh', '$2y$10$S0OvSUVLXk0Zc5t0I2B5M.1aFRetr5q3LFzUAv4XAjUbLu.KyeIbq', '1', NULL, NULL, 'quynh@gmail.com', '2025-01-06 08:50:48'),
(45, 'lanhhhh', '$2y$10$32WKEp2ElfAGZa35CGtcwOvvf0ZzxBX6b4l5bsxUekLUq3KKlw/Tu', '3', 10, NULL, 'thuytrang11@gmail.com', '2025-01-14 22:03:51'),
(46, 'linh', '$2y$10$RqptFhUrkrJrZIJfF90/puz4E1eMkfDilC3FSxUQMEMg2OFOr1iGa', '3', 11, NULL, 'anlinh3011@gmail.com', '2025-01-14 22:07:07'),
(47, 'dfgdd', '$2y$10$RyC4B2t.JEpta3Ek5mBqw.utA.oekMOcU05XhYYsjCcFqzssaTFAq', '3', 3, NULL, 'thuytrang11@gmail.com', '2025-01-25 21:53:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacttt`
--
ALTER TABLE `contacttt`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `curriculum`
--
ALTER TABLE `curriculum`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `eventphoto`
--
ALTER TABLE `eventphoto`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grade`
--
ALTER TABLE `grade`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slides`
--
ALTER TABLE `slides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `StudentID` (`StudentID`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time`
--
ALTER TABLE `time`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timetable`
--
ALTER TABLE `timetable`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=415;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contacttt`
--
ALTER TABLE `contacttt`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `curriculum`
--
ALTER TABLE `curriculum`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `eventphoto`
--
ALTER TABLE `eventphoto`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `grade`
--
ALTER TABLE `grade`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `slides`
--
ALTER TABLE `slides`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `time`
--
ALTER TABLE `time`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `timetable`
--
ALTER TABLE `timetable`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=641;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
COMMIT;

ALTER TABLE students
ADD Email VARCHAR(100) DEFAULT NULL,
ADD PhotoPath VARCHAR(255) DEFAULT NULL;

CREATE TABLE banks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bank_name VARCHAR(100) NOT NULL,
    account_number VARCHAR(50) NOT NULL,
    account_holder VARCHAR(100) NOT NULL,
    qr_code_path VARCHAR(255) NOT NULL
);

CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    bank_id INT NOT NULL,
    payment_note TEXT,
    payment_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bank_id) REFERENCES banks(id)
);

INSERT INTO banks (bank_name, account_number, account_holder, qr_code_path) 
VALUES ('Vietcombank', '1234567890', 'Mầm Non 11', 'uploads/sample_qr.jpg');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;