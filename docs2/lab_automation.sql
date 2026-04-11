-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 04, 2026 at 03:53 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lab_automation`
--

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `type` varchar(10) DEFAULT 'direct',
  `name` varchar(100) DEFAULT NULL,
  `creator_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversation_participants`
--

CREATE TABLE `conversation_participants` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_pinned` tinyint(4) DEFAULT 0,
  `is_deleted` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_logs`
--

CREATE TABLE `email_logs` (
  `id` int(11) NOT NULL,
  `email_type` varchar(50) NOT NULL,
  `recipient` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `status` enum('success','failed') NOT NULL,
  `error_message` text DEFAULT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_logs`
--

INSERT INTO `email_logs` (`id`, `email_type`, `recipient`, `subject`, `status`, `error_message`, `sent_at`) VALUES
(1, 'welcome', 'tester@example.com', 'Welcome to Lab Automation - Let\'s Get Started!', 'success', NULL, '2025-12-26 15:06:07'),
(2, 'welcome', 'analyst@gmail.com', 'Welcome to Lab Automation - Let\'s Get Started!', 'success', NULL, '2025-12-26 18:28:13'),
(3, 'welcome', 'supplier@gmail.com', 'Welcome to Lab Automation - Let\'s Get Started!', 'success', NULL, '2025-12-26 18:28:54'),
(4, 'notification', 'Admins', 'New Activity Notification - 21:13', 'success', NULL, '2025-12-26 20:13:46'),
(5, 'notification', 'Admins', 'New Activity Notification - 21:20', 'success', NULL, '2025-12-26 20:20:50'),
(6, 'notification', 'Admins', 'New Activity Notification - 12:00', 'success', NULL, '2025-12-27 11:00:33'),
(7, 'notification', 'Admins', 'New Activity Notification - 12:03', 'success', NULL, '2025-12-27 11:04:04'),
(8, 'notification', 'Admins', 'New Activity Notification - 12:04', 'success', NULL, '2025-12-27 11:04:16'),
(9, 'notification', 'Admins', 'New Activity Notification - 12:59', 'success', NULL, '2025-12-27 11:59:19'),
(10, 'notification', 'Admins', 'New Activity Notification - 18:29', 'success', NULL, '2025-12-30 17:29:29'),
(11, 'notification', 'Admins', 'New Activity Notification - 20:28', 'success', NULL, '2025-12-30 19:28:38'),
(12, 'notification', 'Admins', 'New Activity Notification - 21:52', 'success', NULL, '2025-12-30 20:52:57'),
(13, 'notification', 'Admins', 'New Activity Notification - 21:53', 'success', NULL, '2025-12-30 20:53:11'),
(14, 'notification', 'Admins', 'New Activity Notification - 04:46', 'success', NULL, '2025-12-31 03:46:12'),
(15, 'notification', 'Admins', 'New Activity Notification - 04:47', 'success', NULL, '2025-12-31 03:47:36'),
(16, 'notification', 'Admins', 'New Activity Notification - 04:51', 'success', NULL, '2025-12-31 03:51:17'),
(17, 'notification', 'Admins', 'New Activity Notification - 04:51', 'success', NULL, '2025-12-31 03:51:30'),
(18, 'notification', 'Admins', 'New Activity Notification - 04:52', 'success', NULL, '2025-12-31 03:52:38'),
(19, 'notification', 'Admins', 'New Activity Notification - 04:53', 'success', NULL, '2025-12-31 03:53:40'),
(20, 'notification', 'Admins', 'New Activity Notification - 05:04', 'success', NULL, '2025-12-31 04:04:05'),
(21, 'notification', 'Admins', 'New Activity Notification - 07:48', 'success', NULL, '2025-12-31 06:48:45'),
(22, 'notification', 'Admins', 'New Activity Notification - 07:48', 'success', NULL, '2025-12-31 06:48:57'),
(23, 'notification', 'Admins', 'New Activity Notification - 07:49', 'success', NULL, '2025-12-31 06:49:41'),
(24, 'notification', 'Admins', 'New Activity Notification - 07:49', 'success', NULL, '2025-12-31 06:49:44'),
(25, 'notification', 'Admins', 'New Activity Notification - 07:50', 'success', NULL, '2025-12-31 06:51:00'),
(26, 'notification', 'Admins', 'New Activity Notification - 07:51', 'success', NULL, '2025-12-31 06:51:14'),
(27, 'notification', 'Admins', 'New Activity Notification - 07:53', 'success', NULL, '2025-12-31 06:53:11'),
(28, 'notification', 'Admins', 'New Activity Notification - 07:53', 'success', NULL, '2025-12-31 06:53:20'),
(29, 'notification', 'Admins', 'New Activity Notification - 07:59', 'success', NULL, '2025-12-31 06:59:29'),
(30, 'notification', 'Admins', 'New Activity Notification - 08:19', 'success', NULL, '2025-12-31 07:19:27'),
(31, 'notification', 'Admins', 'New Activity Notification - 08:19', 'success', NULL, '2025-12-31 07:19:28'),
(32, 'notification', 'Admins', 'New Activity Notification - 08:19', 'success', NULL, '2025-12-31 07:19:34'),
(33, 'welcome', 'ac6855239@gmail.com', 'Welcome to Lab Automation - Let\'s Get Started!', 'success', NULL, '2025-12-31 07:20:15'),
(34, 'welcome', 'tester1@gmail.com', 'Welcome to Lab Automation - Let\'s Get Started!', 'success', NULL, '2025-12-31 07:25:34'),
(35, 'notification', 'Admins', 'New Activity Notification - 08:30', 'success', NULL, '2025-12-31 07:30:22'),
(36, 'notification', 'Admins', 'New Activity Notification - 08:39', 'success', NULL, '2025-12-31 07:39:39'),
(37, 'notification', 'Admins', 'New Activity Notification - 09:10', 'success', NULL, '2025-12-31 08:10:17'),
(38, 'notification', 'Admins', 'New Activity Notification - 09:10', 'success', NULL, '2025-12-31 08:10:22'),
(39, 'notification', 'Admins', 'New Activity Notification - 11:52', 'success', NULL, '2025-12-31 10:52:47'),
(40, 'notification', 'Admins', 'New Activity Notification - 11:55', 'success', NULL, '2025-12-31 10:55:52'),
(41, 'notification', 'Admins', 'New Activity Notification - 11:57', 'success', NULL, '2025-12-31 10:57:49'),
(42, 'notification', 'Admins', 'New Activity Notification - 11:57', 'success', NULL, '2025-12-31 10:57:55'),
(43, 'notification', 'Admins', 'New Activity Notification - 11:57', 'success', NULL, '2025-12-31 10:58:00'),
(44, 'notification', 'Admins', 'New Activity Notification - 11:58', 'success', NULL, '2025-12-31 10:58:20'),
(45, 'notification', 'Admins', 'New Activity Notification - 11:58', 'success', NULL, '2025-12-31 10:58:57'),
(46, 'notification', 'Admins', 'New Activity Notification - 11:59', 'success', NULL, '2025-12-31 10:59:04'),
(47, 'notification', 'Admins', 'New Activity Notification - 11:59', 'success', NULL, '2025-12-31 10:59:33'),
(48, 'notification', 'Admins', 'New Activity Notification - 12:13', 'success', NULL, '2025-12-31 11:14:02'),
(49, 'notification', 'Admins', 'New Activity Notification - 12:14', 'success', NULL, '2025-12-31 11:14:24'),
(50, 'notification', 'Admins', 'New Activity Notification - 12:17', 'success', NULL, '2025-12-31 11:17:47'),
(51, 'notification', 'Admins', 'New Activity Notification - 12:18', 'success', NULL, '2025-12-31 11:18:13'),
(52, 'notification', 'Admins', 'New Activity Notification - 12:21', 'success', NULL, '2025-12-31 11:21:11'),
(53, 'notification', 'Admins', 'New Activity Notification - 12:27', 'success', NULL, '2025-12-31 11:27:43'),
(54, 'notification', 'Admins', 'New Activity Notification - 12:29', 'success', NULL, '2025-12-31 11:29:19'),
(55, 'notification', 'Admins', 'New Activity Notification - 12:31', 'success', NULL, '2025-12-31 11:31:37'),
(56, 'notification', 'Admins', 'New Activity Notification - 12:32', 'success', NULL, '2025-12-31 11:32:22'),
(57, 'notification', 'Admins', 'New Activity Notification - 12:38', 'success', NULL, '2025-12-31 11:38:24'),
(58, 'notification', 'Admins', 'New Activity Notification - 12:38', 'success', NULL, '2025-12-31 11:38:46'),
(59, 'notification', 'Admins', 'New Activity Notification - 12:42', 'success', NULL, '2025-12-31 11:42:24'),
(60, 'notification', 'Admins', 'New Activity Notification - 12:42', 'success', NULL, '2025-12-31 11:42:28'),
(61, 'notification', 'Admins', 'New Activity Notification - 08:46', 'success', NULL, '2026-01-02 07:46:45'),
(62, 'notification', 'Admins', 'New Activity Notification - 08:46', 'success', NULL, '2026-01-02 07:46:48'),
(63, 'notification', 'Admins', 'New Activity Notification - 14:00', 'success', NULL, '2026-01-02 13:01:02'),
(64, 'notification', 'Admins', 'New Activity Notification - 14:04', 'success', NULL, '2026-01-02 13:04:38'),
(65, 'notification', 'Admins', 'New Activity Notification - 14:04', 'success', NULL, '2026-01-02 13:04:48'),
(66, 'notification', 'Admins', 'New Activity Notification - 14:06', 'success', NULL, '2026-01-02 13:07:01'),
(67, 'notification', 'Admins', 'New Activity Notification - 14:10', 'success', NULL, '2026-01-02 13:10:53'),
(68, 'notification', 'Admins', 'New Activity Notification - 14:12', 'success', NULL, '2026-01-02 13:12:05'),
(69, 'notification', 'Admins', 'New Activity Notification - 14:21', 'success', NULL, '2026-01-02 13:21:57'),
(70, 'notification', 'Admins', 'New Activity Notification - 14:22', 'success', NULL, '2026-01-02 13:22:29'),
(71, 'notification', 'Admins', 'New Activity Notification - 14:24', 'success', NULL, '2026-01-02 13:24:47'),
(72, 'notification', 'Admins', 'New Activity Notification - 14:26', 'success', NULL, '2026-01-02 13:26:31'),
(73, 'notification', 'Admins', 'New Activity Notification - 14:29', 'success', NULL, '2026-01-02 13:29:54'),
(74, 'notification', 'Admins', 'New Activity Notification - 14:41', 'success', NULL, '2026-01-02 13:42:02'),
(75, 'notification', 'Admins', 'New Activity Notification - 15:40', 'success', NULL, '2026-01-02 14:40:54'),
(76, 'notification', 'Admins', 'New Activity Notification - 16:22', 'success', NULL, '2026-01-02 15:22:07'),
(77, 'notification', 'Admins', 'New Activity Notification - 17:16', 'success', NULL, '2026-01-02 16:16:57'),
(78, 'notification', 'Admins', 'New Activity Notification - 17:18', 'success', NULL, '2026-01-02 16:19:02'),
(79, 'notification', 'Admins', 'New Activity Notification - 17:19', 'success', NULL, '2026-01-02 16:19:53'),
(80, 'notification', 'Admins', 'New Activity Notification - 19:26', 'success', NULL, '2026-01-02 18:26:39'),
(81, 'notification', 'Admins', 'New Activity Notification - 19:26', 'success', NULL, '2026-01-02 18:26:47'),
(82, 'notification', 'Admins', 'New Activity Notification - 16:27', 'success', NULL, '2026-01-03 15:27:52'),
(83, 'notification', 'Admins', 'New Activity Notification - 16:28', 'success', NULL, '2026-01-03 15:28:04'),
(84, 'notification', 'Admins', 'New Activity Notification - 16:28', 'success', NULL, '2026-01-03 15:28:11'),
(85, 'notification', 'Admins', 'New Activity Notification - 14:46', 'success', NULL, '2026-01-04 13:46:23');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `role` enum('admin','user','analyst','supplier','tester') NOT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `mail_sent` tinyint(1) DEFAULT 0,
  `notify` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `username`, `role`, `action`, `details`, `timestamp`, `mail_sent`, `notify`) VALUES
(53, 71, 'admin', 'user', 'User Role Updated', '{\"target_user_id\":71,\"new_role\":\"admin\"}', '2025-12-26 16:48:07', 0, 1),
(54, 71, 'admin', 'admin', 'User Role Updated', '{\"target_user_id\":72,\"new_role\":\"tester\"}', '2025-12-26 17:31:33', 0, 1),
(55, 71, 'admin', 'admin', 'User Role Updated', '{\"target_user_id\":72,\"new_role\":\"tester\"}', '2025-12-26 18:29:30', 0, 1),
(56, 71, 'admin', 'admin', 'User Role Updated', '{\"target_user_id\":74,\"new_role\":\"supplier\"}', '2025-12-26 18:31:53', 0, 1),
(57, 71, 'admin', 'admin', 'User Role Updated', '{\"target_user_id\":73,\"new_role\":\"analyst\"}', '2025-12-26 18:31:57', 0, 1),
(59, NULL, 'TestSystem', 'admin', 'Test Notification Trigger', '{\"status\":\"testing flow\",\"version\":\"2.0\"}', '2025-12-26 20:06:29', 0, 1),
(60, NULL, 'TestSystem', 'admin', 'Test Notification Trigger', '{\"status\":\"testing flow\",\"version\":\"2.0\"}', '2025-12-26 20:09:05', 0, 1),
(61, NULL, 'TestSystem', 'admin', 'Test Notification Trigger', '{\"status\":\"testing flow\",\"version\":\"2.0\"}', '2025-12-26 20:12:44', 0, 1),
(62, NULL, 'TestSystem', 'admin', 'Test Notification Trigger', '{\"status\":\"testing flow\",\"version\":\"2.0\"}', '2025-12-26 20:13:39', 0, 1),
(63, 71, 'admin', 'admin', 'Unset product results (set to PENDING)', '{\"product_id\":\"PRD-1742-2126\",\"result\":\"pending\"}', '2025-12-26 20:18:49', 0, 1),
(64, 71, 'admin', 'admin', 'User Role Updated', '{\"target_user_id\":74,\"new_role\":\"admin\"}', '2025-12-27 10:56:37', 0, 1),
(65, NULL, 'TestSystem', 'admin', 'Test Notification Trigger', '{\"status\":\"testing flow\",\"version\":\"2.0\"}', '2025-12-27 11:03:57', 0, 1),
(66, 71, 'admin', 'admin', 'User Role Updated', '{\"target_user_id\":74,\"new_role\":\"supplier\"}', '2025-12-27 11:04:08', 0, 1),
(67, 72, 'tester', 'tester', 'Report Created', '{\"test_id\":\"REP-4817-9034\",\"product_id\":91,\"results\":\"passed\"}', '2025-12-27 11:59:14', 0, 1),
(68, 71, 'admin', 'admin', 'Unset product results (set to PENDING)', '{\"product_id\":\"PRD-1742-2126\",\"result\":\"pending\"}', '2025-12-27 12:06:00', 0, 1),
(69, 71, 'admin', 'admin', 'Unset product results (set to PENDING)', '{\"product_id\":\"PRD-9744-5712\",\"result\":\"pending\"}', '2025-12-27 12:06:03', 0, 1),
(70, 71, 'admin', 'admin', 'Unset product results (set to PENDING)', '{\"product_id\":\"PRD-9085-6338\",\"result\":\"pending\"}', '2025-12-27 12:06:05', 0, 1),
(71, 71, 'admin', 'admin', 'Unset product results (set to PENDING)', '{\"product_id\":\"PRD-5643-2483\",\"result\":\"pending\"}', '2025-12-27 12:06:12', 0, 1),
(72, 71, 'admin', 'admin', 'Unset product results (set to PENDING)', '{\"product_id\":\"PRD-3339-0254\",\"result\":\"pending\"}', '2025-12-27 12:06:15', 0, 1),
(73, 74, 'supplier', 'supplier', 'Product Updated', '{\"product_id\":\"PRD-1742-2126\",\"name\":\"Portable Battery Analyzer\"}', '2025-12-30 17:29:22', 0, 1),
(74, 71, 'admin', 'admin', 'Unset product results (set to PENDING)', '{\"product_id\":\"PRD-1742-2126\",\"result\":\"pending\"}', '2025-12-30 19:28:26', 0, 1),
(75, 71, 'admin', 'admin', 'Product Deleted', '{\"product_id\":\"PRD-1742-2126\",\"product_name\":\"Portable Battery Analyzer\"}', '2025-12-30 19:28:32', 0, 1),
(76, 73, 'analyst', 'analyst', 'Marked product as PASSED', '{\"product_id\":\"PRD-2727-0875\",\"result\":\"passed\"}', '2025-12-30 20:33:49', 0, 1),
(77, 73, 'analyst', 'analyst', 'Marked product as FAILED', '{\"product_id\":\"PRD-2727-0875\",\"result\":\"failed\"}', '2025-12-30 20:33:52', 0, 1),
(78, 73, 'analyst', 'analyst', 'Unset product results (set to PENDING)', '{\"product_id\":\"PRD-2727-0875\",\"result\":\"pending\"}', '2025-12-30 20:33:55', 0, 1),
(79, 74, 'supplier', 'supplier', 'Product Added', '{\"product_id\":\"PRD-1582-4964\",\"name\":\"Emma jones\"}', '2025-12-30 20:52:51', 0, 1),
(80, 74, 'supplier', 'supplier', 'Product Updated', '{\"product_id\":\"PRD-1582-4964\",\"name\":\"Emma jones  hhh\"}', '2025-12-30 20:53:06', 0, 1),
(81, 71, 'admin', 'admin', 'User Banned', '{\"target_user_id\":73,\"target_username\":\"analyst\"}', '2025-12-31 03:46:03', 0, 1),
(82, 71, 'admin', 'admin', 'Product Deleted', '{\"product_id\":\"PRD-1582-4964\",\"product_name\":\"Emma jones  hhh\"}', '2025-12-31 03:47:29', 0, 1),
(83, 74, 'supplier', 'supplier', 'Product Updated', '{\"product_id\":\"PRD-2727-0875\",\"name\":\"Clamp Meter\"}', '2025-12-31 03:51:07', 0, 1),
(84, 74, 'supplier', 'supplier', 'Product Added', '{\"product_id\":\"PRD-3395-8536\",\"name\":\"Emma jones\"}', '2025-12-31 03:51:22', 0, 1),
(85, 74, 'supplier', 'supplier', 'Product Deleted', '{\"product_id\":\"PRD-3395-8536\",\"product_name\":\"Emma jones\"}', '2025-12-31 03:52:32', 0, 1),
(86, 74, 'supplier', 'supplier', 'Product Updated', '{\"product_id\":\"PRD-2727-0875\",\"name\":\"Clamp Meter\"}', '2025-12-31 03:53:30', 0, 1),
(87, 74, 'supplier', 'supplier', 'Product Deleted', '{\"product_id\":\"PRD-2727-0875\",\"product_name\":\"Clamp Meter\"}', '2025-12-31 04:03:57', 0, 1),
(88, 74, 'supplier', 'supplier', 'Product Deleted', '{\"product_id\":\"PRD-9744-5712\",\"product_name\":\"Raspberry Pi 4 (4GB)\"}', '2025-12-31 06:48:38', 0, 1),
(89, 74, 'supplier', 'supplier', 'Product Deleted', '{\"product_id\":\"PRD-9627-4876\",\"product_name\":\"Arduino Starter Kit\"}', '2025-12-31 06:48:52', 0, 1),
(90, 71, 'admin', 'admin', 'User Banned', '{\"target_user_id\":74,\"target_username\":\"supplier\"}', '2025-12-31 06:49:35', 0, 1),
(91, 71, 'admin', 'admin', 'User Unbanned', '{\"target_user_id\":74,\"target_username\":\"supplier\"}', '2025-12-31 06:49:39', 0, 1),
(92, 71, 'admin', 'admin', 'Unset product results (set to PENDING)', '{\"product_id\":\"PRD-9085-6338\",\"result\":\"pending\"}', '2025-12-31 06:50:39', 0, 1),
(93, 71, 'admin', 'admin', 'Unset product results (set to PENDING)', '{\"product_id\":\"PRD-9085-6338\",\"result\":\"pending\"}', '2025-12-31 06:50:44', 0, 1),
(94, 71, 'admin', 'admin', 'Product Deleted', '{\"product_id\":\"PRD-4321-2843\",\"product_name\":\"Function Generator\"}', '2025-12-31 06:50:52', 0, 1),
(95, 71, 'admin', 'admin', 'Product Deleted', '{\"product_id\":\"PRD-9085-6338\",\"product_name\":\"DC Power Supply (0\\u201330V)\"}', '2025-12-31 06:51:07', 0, 1),
(96, 71, 'admin', 'admin', 'Product Deleted', '{\"product_id\":\"PRD-5643-2483\",\"product_name\":\"Oscilloscope (2-Channel)\"}', '2025-12-31 06:53:04', 0, 1),
(97, 71, 'admin', 'admin', 'User Banned', '{\"target_user_id\":74,\"target_username\":\"supplier\"}', '2025-12-31 06:53:16', 0, 1),
(98, 71, 'admin', 'admin', 'Unset product results (set to PENDING)', '{\"product_id\":\"PRD-4343-6418\",\"result\":\"pending\"}', '2025-12-31 06:53:25', 0, 1),
(99, 71, 'admin', 'admin', 'Unset product results (set to PENDING)', '{\"product_id\":\"PRD-4343-6418\",\"result\":\"pending\"}', '2025-12-31 06:59:13', 0, 1),
(100, 71, 'admin', 'admin', 'Product Deleted', '{\"product_id\":\"PRD-4343-6418\",\"product_name\":\"Breadboard with Power Supply\"}', '2025-12-31 06:59:24', 0, 1),
(101, 71, 'admin', 'admin', 'User Unbanned', '{\"target_user_id\":74,\"target_username\":\"supplier\"}', '2025-12-31 07:19:17', 0, 1),
(102, 71, 'admin', 'admin', 'User Role Updated', '{\"target_user_id\":74,\"target_username\":\"supplier\",\"new_role\":\"user\"}', '2025-12-31 07:19:23', 0, 1),
(103, 71, 'admin', 'admin', 'User Role Updated', '{\"target_user_id\":74,\"target_username\":\"supplier\",\"new_role\":\"supplier\"}', '2025-12-31 07:19:29', 0, 1),
(104, 71, 'admin', 'admin', 'User Banned', '{\"target_user_id\":74,\"target_username\":\"supplier\"}', '2025-12-31 07:30:14', 0, 1),
(105, 71, 'admin', 'admin', 'Unset product results (set to PENDING)', '{\"product_id\":\"PRD-0032-3700\",\"result\":\"pending\"}', '2025-12-31 07:38:43', 0, 1),
(106, 71, 'admin', 'admin', 'User Unbanned', '{\"target_user_id\":73,\"target_username\":\"analyst\"}', '2025-12-31 07:39:33', 0, 1),
(107, 73, 'analyst', 'analyst', 'Marked product as PASSED', '{\"product_id\":\"PRD-3339-0254\",\"result\":\"passed\"}', '2025-12-31 07:39:58', 0, 1),
(108, 73, 'analyst', 'analyst', 'Marked product as FAILED', '{\"product_id\":\"PRD-3339-0254\",\"result\":\"failed\"}', '2025-12-31 07:40:03', 0, 1),
(109, 73, 'analyst', 'analyst', 'Unset product results (set to PENDING)', '{\"product_id\":\"PRD-3339-0254\",\"result\":\"pending\"}', '2025-12-31 07:40:08', 0, 1),
(110, 71, 'admin', 'admin', 'Tester Banned', '{\"target_user_id\":72,\"target_username\":\"tester\"}', '2025-12-31 08:10:11', 0, 1),
(111, 71, 'admin', 'admin', 'Tester Unbanned', '{\"target_user_id\":72,\"target_username\":\"tester\"}', '2025-12-31 08:10:17', 0, 1),
(112, 73, 'analyst', 'analyst', 'Unset product results (set to PENDING)', '{\"product_id\":\"PRD-3339-0254\",\"result\":\"pending\"}', '2025-12-31 10:51:48', 0, 1),
(113, 71, 'admin', 'admin', 'User Unbanned', '{\"target_user_id\":74,\"target_username\":\"supplier\"}', '2025-12-31 10:52:38', 0, 1),
(114, 71, 'admin', 'admin', 'User Banned', '{\"target_user_id\":77,\"target_username\":\"bilal jaseem\"}', '2025-12-31 10:55:47', 0, 1),
(115, 72, 'tester', 'tester', 'Report Created', '{\"test_id\":\"REP-0607-1272\",\"product_id\":83,\"results\":\"failed\"}', '2025-12-31 10:57:44', 0, 1),
(116, 72, 'tester', 'tester', 'Report Updated', '{\"test_id\":\"REP-3986-2539\",\"product_id\":83,\"results\":\"failed\"}', '2025-12-31 10:57:48', 0, 1),
(117, 72, 'tester', 'tester', 'Report Updated', '{\"test_id\":\"REP-2358-5384\",\"product_id\":83,\"results\":\"failed\"}', '2025-12-31 10:57:54', 0, 1),
(118, 72, 'tester', 'tester', 'Retest Report Generated', '{\"test_id\":\"REP-2358-538\",\"product_id\":83,\"results\":\"failed\"}', '2025-12-31 10:58:14', 0, 1),
(119, 72, 'tester', 'tester', 'Retest Report Generated', '{\"test_id\":\"REP-2358-538\",\"product_id\":83,\"results\":\"failed\"}', '2025-12-31 10:58:52', 0, 1),
(120, 72, 'tester', 'tester', 'Report Deleted', '{\"test_id\":\"REP-2358-538\",\"product_id\":\"83\"}', '2025-12-31 10:58:59', 0, 1),
(121, 72, 'tester', 'tester', 'Report Created', '{\"test_id\":\"REP-0749-7550\",\"product_id\":83,\"results\":\"passed\"}', '2025-12-31 10:59:28', 0, 1),
(122, 72, 'tester', 'tester', 'Retest Report Generated', '{\"test_id\":\"REP-0749-755\",\"product_id\":83,\"results\":\"failed\"}', '2025-12-31 11:13:55', 0, 1),
(123, 72, 'tester', 'tester', 'Retest Report Generated', '{\"test_id\":\"REP-0749-755\",\"product_id\":83,\"results\":\"failed\"}', '2025-12-31 11:14:18', 0, 1),
(124, 72, 'tester', 'tester', 'Report Created', '{\"test_id\":\"REP-2472-7614\",\"product_id\":82,\"results\":\"passed\"}', '2025-12-31 11:17:41', 0, 1),
(125, 72, 'tester', 'tester', 'Retest Report Generated', '{\"test_id\":\"REP-0749-755\",\"product_id\":83,\"results\":\"failed\"}', '2025-12-31 11:18:05', 0, 1),
(126, 72, 'tester', 'tester', 'Retest Report Generated', '{\"test_id\":\"REP-2472-761\",\"product_id\":82,\"results\":\"failed\"}', '2025-12-31 11:21:04', 0, 1),
(127, 72, 'tester', 'tester', 'Retest Report Generated', '{\"test_id\":\"REP-2472-761\",\"product_id\":82,\"results\":\"failed\"}', '2025-12-31 11:27:36', 0, 1),
(128, 72, 'tester', 'tester', 'Retest Report Generated', '{\"test_id\":\"REP-2472-761\",\"product_id\":82,\"results\":\"failed\"}', '2025-12-31 11:29:13', 0, 1),
(129, 72, 'tester', 'tester', 'Report Deleted', '{\"test_id\":\"REP-0749-755\",\"product_id\":\"83\"}', '2025-12-31 11:31:32', 0, 1),
(130, 72, 'tester', 'tester', 'Report Created', '{\"test_id\":\"REP-2484-8842\",\"product_id\":83,\"results\":\"failed\"}', '2025-12-31 11:32:15', 0, 1),
(131, 72, 'tester', 'tester', 'Retest Report Generated', '{\"test_id\":\"REP-2484-884\",\"product_id\":83,\"results\":\"passed\"}', '2025-12-31 11:38:17', 0, 1),
(132, 72, 'tester', 'tester', 'Retest Report Generated', '{\"test_id\":\"REP-2472-761\",\"product_id\":82,\"results\":\"failed\"}', '2025-12-31 11:38:40', 0, 1),
(133, 71, 'admin', 'admin', 'Product Deleted', '{\"product_id\":\"PRD-3339-0254\",\"product_name\":\"Soldering Station\"}', '2025-12-31 11:42:19', 0, 1),
(134, 71, 'admin', 'admin', 'Product Deleted', '{\"product_id\":\"PRD-0032-3700\",\"product_name\":\"Digital Multimeter\"}', '2025-12-31 11:42:23', 0, 1),
(135, 71, 'admin', 'admin', 'User Banned', '{\"target_user_id\":76,\"target_username\":\"testerone\"}', '2026-01-02 07:46:37', 0, 1),
(136, 71, 'admin', 'admin', 'User Banned', '{\"target_user_id\":75,\"target_username\":\"andy cooper\"}', '2026-01-02 07:46:42', 0, 1),
(137, 71, 'admin', 'admin', 'User Unbanned', '{\"target_user_id\":77,\"target_username\":\"bilal jaseem\"}', '2026-01-02 13:00:56', 0, 1),
(138, 71, 'admin', 'admin', 'User Unbanned', '{\"target_user_id\":76,\"target_username\":\"testerone\"}', '2026-01-02 13:04:31', 0, 1),
(139, 71, 'admin', 'admin', 'User Banned', '{\"target_user_id\":77,\"target_username\":\"bilal jaseem\"}', '2026-01-02 13:04:43', 0, 1),
(140, 71, 'admin', 'admin', 'User Banned', '{\"target_user_id\":76,\"target_username\":\"testerone\"}', '2026-01-02 13:06:56', 0, 1),
(141, 71, 'admin', 'admin', 'User Unbanned', '{\"target_user_id\":77,\"target_username\":\"bilal jaseem\"}', '2026-01-02 13:10:49', 0, 1),
(142, 71, 'admin', 'admin', 'User Banned', '{\"target_user_id\":77,\"target_username\":\"bilal jaseem\"}', '2026-01-02 13:11:59', 0, 1),
(143, 71, 'admin', 'admin', 'User Unbanned', '{\"target_user_id\":77,\"target_username\":\"bilal jaseem\"}', '2026-01-02 13:21:52', 0, 1),
(144, 71, 'admin', 'admin', 'User Banned', '{\"target_user_id\":77,\"target_username\":\"bilal jaseem\"}', '2026-01-02 13:22:23', 0, 1),
(145, 71, 'admin', 'admin', 'User Unbanned', '{\"target_user_id\":77,\"target_username\":\"bilal jaseem\"}', '2026-01-02 13:24:41', 0, 1),
(146, 71, 'admin', 'admin', 'User Banned', '{\"target_user_id\":77,\"target_username\":\"bilal jaseem\"}', '2026-01-02 13:26:22', 0, 1),
(147, 71, 'admin', 'admin', 'User Unbanned', '{\"target_user_id\":77,\"target_username\":\"bilal jaseem\"}', '2026-01-02 13:29:49', 0, 1),
(148, 71, 'admin', 'admin', 'User Banned', '{\"target_user_id\":77,\"target_username\":\"bilal jaseem\"}', '2026-01-02 13:41:54', 0, 1),
(149, 71, 'admin', 'admin', 'User Unbanned', '{\"target_user_id\":77,\"target_username\":\"bilal jaseem\"}', '2026-01-02 14:40:48', 0, 1),
(150, 71, 'admin', 'admin', 'User Banned', '{\"target_user_id\":77,\"target_username\":\"bilal jaseem\"}', '2026-01-02 15:22:01', 0, 1),
(151, 71, 'admin', 'admin', 'User Unbanned', '{\"target_user_id\":77,\"target_username\":\"bilal jaseem\"}', '2026-01-02 16:16:50', 0, 1),
(152, 71, 'admin', 'admin', 'User Banned', '{\"target_user_id\":77,\"target_username\":\"bilal jaseem\"}', '2026-01-02 16:18:57', 0, 1),
(153, 71, 'admin', 'admin', 'User Unbanned', '{\"target_user_id\":77,\"target_username\":\"bilal jaseem\"}', '2026-01-02 16:19:47', 0, 1),
(154, 71, 'admin', 'admin', 'User Banned', '{\"target_user_id\":77,\"target_username\":\"bilal jaseem\"}', '2026-01-02 18:26:33', 0, 1),
(155, 71, 'admin', 'admin', 'User Unbanned', '{\"target_user_id\":77,\"target_username\":\"bilal jaseem\"}', '2026-01-02 18:26:40', 0, 1),
(156, 71, 'admin', 'admin', 'User Banned', '{\"target_user_id\":77,\"target_username\":\"bilal jaseem\"}', '2026-01-03 15:27:41', 0, 1),
(157, 71, 'admin', 'admin', 'User Role Updated', '{\"target_user_id\":75,\"target_username\":\"andy cooper\",\"new_role\":\"supplier\"}', '2026-01-03 15:28:00', 0, 1),
(158, 71, 'admin', 'admin', 'User Role Updated', '{\"target_user_id\":75,\"target_username\":\"andy cooper\",\"new_role\":\"user\"}', '2026-01-03 15:28:06', 0, 1),
(159, 71, 'admin', 'admin', 'User Role Updated', '{\"target_user_id\":78,\"target_username\":\"John Hill\",\"new_role\":\"supplier\"}', '2026-01-04 13:46:16', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `file_path` varchar(500) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `product_id` varchar(20) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `results` enum('passed','failed','pending') DEFAULT 'pending',
  `status` enum('pending','tested') DEFAULT 'pending',
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `security_logs`
--

CREATE TABLE `security_logs` (
  `id` int(11) NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `security_logs`
--

INSERT INTO `security_logs` (`id`, `event_type`, `user_id`, `username`, `ip_address`, `details`, `created_at`) VALUES
(1, 'failed_login', 52, 'supplier', '::1', 'Invalid credentials', '2025-12-26 11:04:39'),
(2, 'login_success', 52, 'supplier', '::1', 'User logged in successfully', '2025-12-26 11:12:15'),
(3, 'failed_login', 52, 'supplier', '::1', 'Invalid credentials', '2025-12-26 11:14:02'),
(4, 'user_registration', 70, 'Rutaab Ali', '::1', 'New user registered via Google', '2025-12-26 13:37:34'),
(5, 'user_registration', 71, 'admin', '::1', 'New user registered', '2025-12-26 15:02:05'),
(6, 'user_registration', 72, 'tester', '::1', 'New user registered', '2025-12-26 15:06:07'),
(7, 'admin_action', 71, 'admin', '::1', 'Role changed to admin for user ID 71', '2025-12-26 16:32:50'),
(8, 'admin_action', 71, 'admin', '::1', 'Role changed to user for user ID 71', '2025-12-26 16:39:25'),
(9, 'suspicious', NULL, NULL, '::1', 'Unauthorized access attempt to User Roles Manager', '2025-12-26 16:46:51'),
(10, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-26 16:47:19'),
(11, 'suspicious', NULL, NULL, '::1', 'Unauthorized access attempt to User Roles Manager', '2025-12-26 16:47:42'),
(12, 'admin_action', 71, 'admin', '::1', 'Role changed to admin for user ID 71', '2025-12-26 16:48:07'),
(13, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-26 16:49:24'),
(14, 'admin_action', 72, 'admin', '::1', 'Role changed to tester for user ID 72', '2025-12-26 17:31:33'),
(15, 'user_registration', 73, 'analyst', '::1', 'New user registered', '2025-12-26 18:28:13'),
(16, 'user_registration', 74, 'supplier', '::1', 'New user registered', '2025-12-26 18:28:54'),
(17, 'login_success', 74, 'supplier', '::1', 'User logged in successfully', '2025-12-26 18:29:08'),
(18, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-26 18:29:24'),
(19, 'admin_action', 72, 'admin', '::1', 'Role changed to tester for user ID 72', '2025-12-26 18:29:30'),
(20, 'admin_action', 74, 'admin', '::1', 'Role changed to supplier for user ID 74', '2025-12-26 18:31:53'),
(21, 'admin_action', 73, 'admin', '::1', 'Role changed to analyst for user ID 73', '2025-12-26 18:31:57'),
(22, 'admin_action', 74, 'admin', '::1', 'Role changed to admin for user ID 74', '2025-12-27 10:56:37'),
(23, 'admin_action', 74, 'admin', '::1', 'Role changed to supplier for user ID 74', '2025-12-27 11:04:07'),
(24, 'login_success', 72, 'tester', '::1', 'User logged in successfully', '2025-12-27 11:58:43'),
(25, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-27 12:05:44'),
(26, 'login_success', 73, 'analyst', '::1', 'User logged in successfully', '2025-12-27 15:23:43'),
(27, 'login_success', 74, 'supplier', '::1', 'User logged in successfully', '2025-12-27 15:24:39'),
(28, 'login_success', 72, 'tester', '::1', 'User logged in successfully', '2025-12-27 16:07:10'),
(29, 'login_success', 74, 'supplier', '::1', 'User logged in successfully', '2025-12-27 16:27:44'),
(30, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-27 16:29:04'),
(31, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-27 16:29:46'),
(32, 'login_success', 74, 'supplier', '::1', 'User logged in successfully', '2025-12-27 16:30:02'),
(33, 'failed_login', NULL, 'user', '::1', 'Invalid credentials', '2025-12-27 16:34:07'),
(34, 'login_success', 74, 'supplier', '::1', 'User logged in successfully', '2025-12-27 16:34:35'),
(35, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-27 16:42:35'),
(36, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-28 07:09:42'),
(37, 'login_success', 73, 'analyst', '::1', 'User logged in successfully', '2025-12-28 07:50:26'),
(38, 'login_success', 73, 'analyst', '::1', 'User logged in successfully', '2025-12-28 07:55:56'),
(39, 'login_success', 73, 'analyst', '::1', 'User logged in successfully', '2025-12-28 07:57:50'),
(40, 'login_success', 73, 'analyst', '::1', 'User logged in successfully', '2025-12-28 19:58:52'),
(41, 'login_success', 73, 'analyst', '::1', 'User logged in successfully', '2025-12-28 19:59:25'),
(42, 'login_success', 74, 'supplier', '::1', 'User logged in successfully', '2025-12-28 20:10:26'),
(43, 'login_success', 72, 'tester', '::1', 'User logged in successfully', '2025-12-29 03:51:54'),
(44, 'login_success', 72, 'tester', '::1', 'User logged in successfully', '2025-12-29 13:02:16'),
(45, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-29 17:22:49'),
(46, 'login_success', 73, 'analyst', '::1', 'User logged in successfully', '2025-12-29 17:42:06'),
(47, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-29 17:43:15'),
(48, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-29 17:44:47'),
(49, 'login_success', 73, 'analyst', '::1', 'User logged in successfully', '2025-12-30 07:11:39'),
(50, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-30 07:26:25'),
(51, 'login_success', 74, 'supplier', '::1', 'User logged in successfully', '2025-12-30 07:33:09'),
(52, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-30 17:15:36'),
(53, 'login_success', 74, 'supplier', '::1', 'User logged in successfully', '2025-12-30 17:29:08'),
(54, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-30 18:55:50'),
(55, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-30 20:03:29'),
(56, 'login_success', 73, 'analyst', '::1', 'User logged in successfully', '2025-12-30 20:03:57'),
(57, 'login_success', 73, 'analyst', '::1', 'User logged in successfully', '2025-12-30 20:36:18'),
(58, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-30 20:36:47'),
(59, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-30 20:46:11'),
(60, 'login_success', 74, 'supplier', '::1', 'User logged in successfully', '2025-12-30 20:46:33'),
(61, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-31 03:32:12'),
(62, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-31 03:44:05'),
(63, 'login_success', 74, 'supplier', '::1', 'User logged in successfully', '2025-12-31 03:50:46'),
(64, 'login_success', 74, 'supplier', '::1', 'User logged in successfully', '2025-12-31 06:42:38'),
(65, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-31 06:49:25'),
(66, 'user_registration', 75, 'andy cooper', '::1', 'New user registered via Google', '2025-12-31 07:20:15'),
(67, 'login_success', 75, 'andy cooper', '::1', 'User logged in via Google', '2025-12-31 07:21:48'),
(68, 'login_success', 75, 'andy cooper', '::1', 'User logged in via Google', '2025-12-31 07:24:38'),
(69, 'user_registration', 76, 'testerone', '::1', 'New user registered', '2025-12-31 07:25:34'),
(70, 'failed_login', NULL, 'analyst', '::1', 'Invalid credentials', '2025-12-31 07:39:02'),
(71, 'failed_login', NULL, 'analyst', '::1', 'Invalid credentials', '2025-12-31 07:39:12'),
(72, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-31 07:39:23'),
(73, 'login_success', 73, 'analyst', '::1', 'User logged in successfully', '2025-12-31 07:39:47'),
(74, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-31 07:40:42'),
(75, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-31 10:47:23'),
(76, 'login_success', 73, 'analyst', '::1', 'User logged in successfully', '2025-12-31 10:48:23'),
(77, 'failed_login', NULL, 'supplier', '::1', 'Invalid credentials', '2025-12-31 10:52:10'),
(78, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-31 10:52:25'),
(79, 'login_success', 72, 'tester', '::1', 'User logged in successfully', '2025-12-31 10:56:21'),
(80, 'suspicious', NULL, NULL, '::1', 'Unauthorized access attempt to User Roles Manager', '2025-12-31 11:41:02'),
(81, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-31 11:41:25'),
(82, 'login_success', 72, 'tester', '::1', 'User logged in successfully', '2025-12-31 11:42:43'),
(83, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-31 11:58:44'),
(84, 'login_success', 73, 'analyst', '::1', 'User logged in successfully', '2025-12-31 12:00:29'),
(85, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-31 12:00:51'),
(86, 'login_success', 70, 'Rutaab Ali', '::1', 'User logged in via Google', '2025-12-31 13:53:39'),
(87, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2025-12-31 14:32:57'),
(88, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2026-01-02 07:16:54'),
(89, 'login_success', 73, 'analyst', '::1', 'User logged in successfully', '2026-01-02 07:45:25'),
(90, 'login_success', 73, 'analyst', '::1', 'User logged in successfully', '2026-01-02 07:45:47'),
(91, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2026-01-02 07:46:20'),
(92, 'login_success', 74, 'supplier', '::1', 'User logged in successfully', '2026-01-02 07:50:08'),
(93, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2026-01-02 07:53:15'),
(94, 'login_success', 73, 'analyst', '::1', 'User logged in successfully', '2026-01-02 07:53:30'),
(95, 'login_success', 74, 'supplier', '::1', 'User logged in successfully', '2026-01-02 07:53:43'),
(96, 'failed_login', NULL, 'rutaabali953@gmail.com', '::1', 'Invalid credentials', '2026-01-02 07:53:54'),
(97, 'failed_login', NULL, 'user', '::1', 'Invalid credentials', '2026-01-02 07:54:27'),
(98, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2026-01-02 07:54:36'),
(99, 'login_success', 70, 'Rutaab Ali', '::1', 'User logged in via Google', '2026-01-02 07:55:01'),
(100, 'login_success', 73, 'analyst', '::1', 'User logged in successfully', '2026-01-02 07:58:06'),
(101, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2026-01-02 13:00:39'),
(102, 'login_success', 73, 'analyst', '::1', 'User logged in successfully', '2026-01-02 15:22:49'),
(103, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2026-01-02 15:28:17'),
(104, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2026-01-02 15:37:50'),
(105, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2026-01-02 15:37:53'),
(106, 'login_success', 73, 'analyst', '::1', 'User logged in successfully', '2026-01-02 15:42:21'),
(107, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2026-01-02 16:16:36'),
(108, 'login_success', 73, 'analyst', '::1', 'User logged in successfully', '2026-01-02 18:27:06'),
(109, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2026-01-02 18:56:53'),
(110, 'login_success', 72, 'tester', '::1', 'User logged in successfully', '2026-01-02 19:40:51'),
(111, 'login_success', 72, 'tester', '::1', 'User logged in successfully', '2026-01-02 19:43:32'),
(112, 'login_success', 74, 'supplier', '::1', 'User logged in successfully', '2026-01-02 19:43:54'),
(113, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2026-01-02 19:58:38'),
(114, 'login_success', 73, 'analyst', '::1', 'User logged in successfully', '2026-01-03 07:23:24'),
(115, 'login_success', 74, 'supplier', '::1', 'User logged in successfully', '2026-01-03 07:24:45'),
(116, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2026-01-03 07:28:33'),
(117, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2026-01-04 10:39:53'),
(118, 'login_success', 74, 'supplier', '::1', 'User logged in successfully', '2026-01-04 12:35:58'),
(119, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2026-01-04 12:36:29'),
(120, 'login_success', 78, 'John Hill', '::1', 'User logged in via Google', '2026-01-04 13:49:18'),
(121, 'login_success', 74, 'supplier', '::1', 'User logged in successfully', '2026-01-04 14:00:38'),
(122, 'login_success', 71, 'admin', '::1', 'User logged in successfully', '2026-01-04 14:05:04'),
(123, 'login_success', 79, 'John Hill', '::1', 'User logged in via Google', '2026-01-04 14:09:28'),
(124, 'login_success', 79, 'John Hill', '::1', 'User logged in via Google', '2026-01-04 14:15:10'),
(125, 'login_success', 75, 'andy cooper', '::1', 'User logged in via Google', '2026-01-04 14:46:16');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `category`, `setting_key`, `setting_value`, `created_at`, `updated_at`) VALUES
(1, 'mail', 'host', 'smtp.gmail.com', '2025-12-25 17:04:10', '2025-12-25 17:04:10'),
(2, 'mail', 'username', 'REPLACE_WITH_SMTP_USERNAME', '2025-12-25 17:04:10', '2025-12-25 17:04:10'),
(3, 'mail', 'password', 'REPLACE_WITH_SMTP_APP_PASSWORD', '2025-12-25 17:04:10', '2025-12-25 17:04:10'),
(4, 'mail', 'port', '465', '2025-12-25 17:04:10', '2025-12-25 17:04:10'),
(5, 'mail', 'encryption', 'ssl', '2025-12-25 17:04:10', '2025-12-25 17:04:10'),
(6, 'cloudflare', 'site_key', '0x4AAAAAACH50FVSWDr8HGam', '2025-12-25 17:04:10', '2025-12-25 17:04:10'),
(7, 'cloudflare', 'secret_key', 'REPLACE_WITH_CLOUDFLARE_SECRET_KEY', '2025-12-25 17:04:10', '2025-12-25 17:04:10'),
(8, 'cloudflare', 'cdn', 'https://challenges.cloudflare.com/turnstile/v0/api.js', '2025-12-25 17:04:10', '2025-12-25 17:04:10'),
(9, 'google', 'client_id', 'REPLACE_WITH_GOOGLE_CLIENT_ID', '2025-12-25 17:04:10', '2025-12-25 17:04:10'),
(10, 'google', 'project_id', 'lab-482107', '2025-12-25 17:04:10', '2025-12-25 17:04:10'),
(11, 'google', 'auth_uri', 'https://accounts.google.com/o/oauth2/auth', '2025-12-25 17:04:10', '2025-12-25 17:04:10'),
(12, 'google', 'token_uri', 'https://oauth2.googleapis.com/token', '2025-12-25 17:04:10', '2025-12-25 17:04:10'),
(13, 'google', 'auth_provider_x509_cert_url', 'https://www.googleapis.com/oauth2/v1/certs', '2025-12-25 17:04:10', '2025-12-25 17:04:10'),
(14, 'google', 'client_secret', 'REPLACE_WITH_GOOGLE_CLIENT_SECRET', '2025-12-25 17:04:10', '2025-12-25 17:04:10'),
(15, 'google', 'redirect_uris', '[\"http:\\/\\/localhost\\/lab\\/users\\/register.php\",\"http:\\/\\/localhost:82\\/lab\\/users\\/register.php\",\"http:\\/\\/localhost\\/lab\\/users\\/login.php\",\"http:\\/\\/localhost:82\\/lab\\/users\\/login.php\"]', '2025-12-25 17:04:10', '2025-12-25 17:04:10'),
(16, 'google', 'javascript_origins', '[\"http:\\/\\/localhost\",\"http:\\/\\/localhost:82\"]', '2025-12-25 17:04:10', '2025-12-25 17:04:10'),
(17, 'general', 'maintenance_mode', '0', '2025-12-30 19:01:32', '2025-12-30 19:01:32'),
(18, 'general', 'site_name', 'Lab Automation', '2025-12-30 19:01:32', '2025-12-30 19:01:32');

-- --------------------------------------------------------

--
-- Table structure for table `test_reports`
--

CREATE TABLE `test_reports` (
  `id` int(11) NOT NULL,
  `tester_id` int(11) NOT NULL,
  `test_id` char(12) NOT NULL,
  `product_id` int(11) NOT NULL,
  `results` enum('passed','failed','pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_nopad_ci DEFAULT 'pending',
  `pdf` varchar(255) DEFAULT NULL,
  `seen_by_admin` tinyint(1) DEFAULT 0,
  `seen_by_analyst` tinyint(1) DEFAULT 0,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_img` varchar(255) NOT NULL,
  `role` enum('admin','user','analyst','supplier','tester') NOT NULL DEFAULT 'user',
  `status` enum('active','banned') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `bg-img` varchar(255) NOT NULL,
  `otp_code` varchar(6) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL,
  `greetings` tinyint(1) DEFAULT 0 COMMENT '0 = not sent, 1 = sent',
  `preferred_language` varchar(10) DEFAULT 'en'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `profile_img`, `role`, `status`, `created_at`, `last_login`, `bg-img`, `otp_code`, `otp_expiry`, `greetings`, `preferred_language`) VALUES
(70, 'Rutaab Ali', 'rutaabali953@gmail.com', 'e00412a4c63878569a92646838fc7b5b', 'uploads/users/default.png', 'user', 'active', '2025-12-26 13:37:29', '2026-01-02 07:55:01', '', NULL, NULL, 1, 'en'),
(71, 'admin', 'rutaabali3@gmail.com', 'e99a18c428cb38d5f260853678922e03', 'uploads/users/default.png', 'admin', 'active', '2025-12-26 15:02:02', '2026-01-04 14:05:04', '', NULL, NULL, 1, 'en'),
(72, 'tester', 'tester@example.com', 'e99a18c428cb38d5f260853678922e03', 'uploads/users/default.png', 'tester', 'active', '2025-12-26 15:06:04', '2026-01-02 19:43:32', '', NULL, NULL, 1, 'en'),
(73, 'analyst', 'analyst@gmail.com', 'e99a18c428cb38d5f260853678922e03', 'uploads/users/default.png', 'analyst', 'active', '2025-12-26 18:28:09', '2026-01-03 07:23:24', '', NULL, NULL, 1, 'en'),
(74, 'supplier', 'supplier@gmail.com', 'e99a18c428cb38d5f260853678922e03', 'uploads/users/default.png', 'supplier', 'active', '2025-12-26 18:28:50', '2026-01-04 14:00:38', '', NULL, NULL, 1, 'en'),
(75, 'andy cooper', 'ac6855239@gmail.com', '5ff5f101b716b51373797df6a45a0675', 'uploads/users/default.png', 'user', 'banned', '2025-12-31 07:20:11', '2026-01-04 14:46:16', '', NULL, NULL, 1, 'en'),
(76, 'testerone', 'tester1@gmail.com', 'e99a18c428cb38d5f260853678922e03', 'uploads/users/default.png', 'user', 'banned', '2025-12-31 07:25:31', NULL, '', NULL, NULL, 1, 'en'),
(77, 'bilal jaseem', 'bilaljaseem290@gmail.com', 'c96d9bdefc862337c1f75745aded3f42', 'uploads/users/default.png', 'user', 'banned', '2025-12-31 07:26:08', NULL, '', NULL, NULL, 1, 'en');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conversation_participants`
--
ALTER TABLE `conversation_participants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_conversation_user` (`conversation_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `email_logs`
--
ALTER TABLE `email_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email_type` (`email_type`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_sent_at` (`sent_at`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_conversation` (`conversation_id`),
  ADD KEY `idx_sender` (`sender_id`),
  ADD KEY `idx_sent_at` (`sent_at`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_id` (`product_id`),
  ADD UNIQUE KEY `product_id_2` (`product_id`);

--
-- Indexes for table `security_logs`
--
ALTER TABLE `security_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_event_type` (`event_type`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category` (`category`,`setting_key`);

--
-- Indexes for table `test_reports`
--
ALTER TABLE `test_reports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `test_id` (`test_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `fk_test_reports_user` (`tester_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `conversation_participants`
--
ALTER TABLE `conversation_participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `email_logs`
--
ALTER TABLE `email_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `security_logs`
--
ALTER TABLE `security_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `test_reports`
--
ALTER TABLE `test_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `conversation_participants`
--
ALTER TABLE `conversation_participants`
  ADD CONSTRAINT `conversation_participants_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversation_participants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `test_reports`
--
ALTER TABLE `test_reports`
  ADD CONSTRAINT `fk_test_reports_user` FOREIGN KEY (`tester_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `test_reports_ibfk_1` FOREIGN KEY (`tester_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `test_reports_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
