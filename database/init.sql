-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 13, 2021 at 09:50 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `octopus`
--

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `admin_name`, `password`, `display_name`, `create_time`, `update_time`, `delete_time`, `status`) VALUES
(1, 'admin', '$2y$10$Exku7T200WS2JQXZxodqne6HDDDyLtgKsC.edJVkEqkSLPHSeu2my', 'Admin', '2021-04-13 13:28:28', '2021-04-13 13:32:52', NULL, 1);

--
-- Dumping data for table `auth_admin_group`
--

INSERT INTO `auth_admin_group` (`id`, `admin_id`, `group_id`) VALUES
(225, 1, 53);

--
-- Dumping data for table `auth_group`
--

INSERT INTO `auth_group` (`id`, `parent_id`, `group_name`, `create_time`, `update_time`, `delete_time`, `status`) VALUES
(53, 0, 'Admin Group', '2020-09-21 00:10:30', '2021-04-09 23:57:46', NULL, 1);

--
-- Dumping data for table `auth_group_rule`
--

INSERT INTO `auth_group_rule` (`id`, `group_id`, `rule_id`) VALUES
(44, 53, 18),
(51, 53, 19),
(42, 53, 20),
(50, 53, 21),
(49, 53, 35),
(52, 53, 46),
(53, 53, 47),
(60, 53, 55),
(59, 53, 56),
(58, 53, 57),
(57, 53, 58),
(56, 53, 59),
(55, 53, 60),
(54, 53, 61),
(67, 53, 63),
(66, 53, 64),
(65, 53, 65),
(64, 53, 66),
(63, 53, 67),
(62, 53, 68),
(61, 53, 69),
(75, 53, 71),
(74, 53, 72),
(73, 53, 73),
(72, 53, 74),
(71, 53, 75),
(70, 53, 76),
(69, 53, 77),
(76, 53, 80),
(77, 53, 81),
(82, 53, 86),
(88, 53, 87),
(87, 53, 88),
(86, 53, 89),
(85, 53, 90),
(84, 53, 91),
(83, 53, 92),
(194, 53, 293);

--
-- Dumping data for table `auth_rule`
--

INSERT INTO `auth_rule` (`id`, `parent_id`, `rule_path`, `rule_title`, `type`, `condition`, `create_time`, `update_time`, `delete_time`, `status`) VALUES
(16, 0, '', 'Admin', 1, '', '2020-09-21 00:05:53', '2020-09-30 14:42:55', NULL, 1),
(18, 16, 'api/admin/add', 'Admin Add', 1, '', '2020-09-21 00:07:10', '2020-10-13 18:50:39', NULL, 1),
(19, 16, 'api/admin/save', 'Admin Save', 1, '', '2020-09-21 00:07:27', '2020-09-23 23:31:58', NULL, 1),
(20, 16, 'api/admin/read', 'Admin Read', 1, '', '2020-09-21 00:08:15', '2020-10-13 18:50:50', NULL, 1),
(21, 16, 'api/admin/update', 'Admin Update', 1, '', '2020-09-21 00:08:33', '2020-09-23 23:32:07', NULL, 1),
(35, 16, 'api/admin/delete', 'Admin Delete', 1, '', '2020-09-23 00:22:48', '2020-09-23 23:32:14', NULL, 1),
(46, 16, 'api/admin/home', 'Admin Home', 1, '', '2020-09-29 16:10:03', '2020-10-13 18:51:17', NULL, 1),
(47, 16, 'api/admin/restore', 'Admin Restore', 1, '', '2020-09-29 18:47:59', '2020-09-29 18:48:14', NULL, 1),
(54, 0, '', 'Group', 1, '', '2020-09-30 14:42:43', '2020-09-30 14:42:50', NULL, 1),
(55, 54, 'api/auth_group/home', 'Group Home', 1, '', '2020-09-30 14:43:14', '2020-09-30 14:43:43', NULL, 1),
(56, 54, 'api/auth_group/add', 'Group Add', 1, '', '2020-09-30 14:43:51', '2020-09-30 14:44:03', NULL, 1),
(57, 54, 'api/auth_group/save', 'Group Save', 1, '', '2020-09-30 14:44:13', '2020-09-30 14:44:20', NULL, 1),
(58, 54, 'api/auth_group/read', 'Group Read', 1, '', '2020-09-30 14:44:25', '2020-09-30 14:44:34', NULL, 1),
(59, 54, 'api/auth_group/update', 'Group Update', 1, '', '2020-09-30 14:44:40', '2020-09-30 14:44:53', NULL, 1),
(60, 54, 'api/auth_group/delete', 'Group Delete', 1, '', '2020-09-30 14:44:57', '2020-09-30 14:45:21', NULL, 1),
(61, 54, 'api/auth_group/restore', 'Group Restore', 1, '', '2020-09-30 14:45:27', '2020-09-30 14:45:36', NULL, 1),
(62, 0, '', 'Rule', 1, '', '2020-09-30 14:46:28', '2020-10-08 15:06:11', NULL, 1),
(63, 62, 'api/auth_rule/home', 'Rule Home', 1, '', '2020-09-30 14:46:35', '2020-09-30 14:46:48', NULL, 1),
(64, 62, 'api/auth_rule/add', 'Rule Add', 1, '', '2020-09-30 14:46:52', '2020-09-30 14:47:01', NULL, 1),
(65, 62, 'api/auth_rule/save', 'Rule Save', 1, '', '2020-09-30 14:47:05', '2020-09-30 14:47:12', NULL, 1),
(66, 62, 'api/auth_rule/read', 'Rule Read', 1, '', '2020-09-30 14:47:17', '2020-09-30 14:47:24', NULL, 1),
(67, 62, 'api/auth_rule/update', 'Rule Update', 1, '', '2020-09-30 14:47:31', '2020-09-30 14:47:39', NULL, 1),
(68, 62, 'api/auth_rule/delete', 'Rule Delete', 1, '', '2020-09-30 14:47:44', '2020-10-10 15:54:53', NULL, 1),
(69, 62, 'api/auth_rule/restore', 'Rule Restore', 1, '', '2020-09-30 14:47:57', '2020-09-30 14:48:06', NULL, 1),
(70, 0, '', 'Model', 1, '', '2020-09-30 22:44:29', '2020-10-06 22:32:40', NULL, 1),
(71, 70, 'api/model/home', 'Model Home', 1, '', '2020-10-06 22:32:42', '2020-10-06 22:33:46', NULL, 1),
(72, 70, 'api/model/add', 'Model Add', 1, '', '2020-10-06 22:33:21', '2020-10-06 22:33:37', NULL, 1),
(73, 70, 'api/model/save', 'Model Save', 1, '', '2020-10-06 22:34:10', '2020-10-06 22:34:23', NULL, 1),
(74, 70, 'api/model/read', 'Model Read', 1, '', '2020-10-06 22:34:41', '2020-10-06 22:37:32', NULL, 1),
(75, 70, 'api/model/update', 'Model Update', 1, '', '2020-10-06 22:38:18', '2020-10-06 22:38:37', NULL, 1),
(76, 70, 'api/model/delete', 'Model Delete', 1, '', '2020-10-06 22:38:39', '2020-10-06 22:38:56', NULL, 1),
(77, 70, 'api/model/restore', 'Model Restore', 1, '', '2020-10-06 22:39:02', '2020-10-06 22:39:18', NULL, 1),
(80, 70, 'api/model/design', 'Model Design Read', 1, '', '2020-10-09 14:31:05', '2020-10-09 22:04:24', NULL, 1),
(81, 70, 'api/model/design_update', 'Model Design Update', 1, '', '2020-10-09 22:03:28', '2020-10-09 22:10:49', NULL, 1),
(85, 0, '', 'Menu', 1, '', '2020-10-14 11:29:56', '2020-10-21 22:32:40', NULL, 1),
(86, 85, 'api/menu/home', 'Menu Home', 1, '', '2020-10-14 11:30:12', '2020-10-14 11:30:37', NULL, 1),
(87, 85, 'api/menu/add', 'Menu Add', 1, '', '2020-10-14 12:19:55', '2020-10-14 12:20:13', NULL, 1),
(88, 85, 'api/menu/save', 'Menu Save', 1, '', '2020-10-14 12:20:17', '2020-10-14 12:21:43', NULL, 1),
(89, 85, 'api/menu/read', 'Menu Read', 1, '', '2020-10-14 12:21:50', '2020-10-14 12:22:04', NULL, 1),
(90, 85, 'api/menu/update', 'Menu Update', 1, '', '2020-10-14 12:22:06', '2020-10-14 12:22:18', NULL, 1),
(91, 85, 'api/menu/delete', 'Menu Delete', 1, '', '2020-10-14 12:22:25', '2020-10-14 12:22:38', NULL, 1),
(92, 85, 'api/menu/restore', 'Menu Restore', 1, '', '2020-10-14 12:22:40', '2020-10-14 12:22:54', NULL, 1),
(293, 16, 'api/admin/login', 'Admin Login', 1, '', '2020-10-22 15:27:01', '2020-10-22 15:27:27', NULL, 1);

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `parent_id`, `menu_name`, `icon`, `path`, `hide_in_menu`, `hide_children_in_menu`, `flat_menu`, `status`, `create_time`, `update_time`, `delete_time`) VALUES
(5, 0, 'admin-list', 'icon-user', '/basic-list/api/admins', 0, 0, 0, 1, '2020-10-14 16:02:58', '2020-10-15 10:45:48', NULL),
(6, 5, 'add', '', '/basic-list/api/admins/add', 1, 0, 0, 1, '2020-10-14 16:04:00', '2020-10-23 22:53:15', NULL),
(7, 5, 'edit', '', '/basic-list/api/admins/:id', 1, 0, 0, 1, '2020-10-14 16:04:29', '2020-10-23 22:53:10', NULL),
(8, 0, 'group-list', 'icon-team', '/basic-list/api/groups', 0, 0, 0, 1, '2020-10-14 16:05:15', '2020-10-14 16:05:57', NULL),
(9, 8, 'add', '', '/basic-list/api/groups/add', 1, 0, 0, 1, '2020-10-14 16:06:02', '2020-10-14 16:06:14', NULL),
(10, 8, 'edit', '', '/basic-list/api/groups/:id', 1, 0, 0, 1, '2020-10-14 16:06:24', '2020-10-14 16:07:59', NULL),
(11, 0, 'rule-list', 'icon-table', '/basic-list/api/rules', 0, 0, 0, 1, '2020-10-14 16:08:13', '2020-10-14 16:08:28', NULL),
(12, 11, 'add', '', '/basic-list/api/rules/add', 1, 0, 0, 1, '2020-10-14 16:08:31', '2020-10-14 16:08:46', NULL),
(13, 11, 'edit', '', '/basic-list/api/rules/:id', 1, 0, 0, 1, '2020-10-14 16:08:49', '2020-10-14 16:08:58', NULL),
(14, 0, 'menu-list', 'icon-menu', '/basic-list/api/menus', 0, 0, 0, 1, '2020-10-14 16:09:14', '2020-10-15 10:47:46', NULL),
(15, 14, 'add', '', '/basic-list/api/menus/add', 1, 0, 0, 1, '2020-10-14 16:09:26', '2020-10-14 16:09:47', NULL),
(16, 14, 'edit', '', '/basic-list/api/menus/:id', 1, 0, 0, 1, '2020-10-14 16:09:49', '2020-10-14 16:10:04', NULL),
(19, 0, 'model-list', 'icon-appstore', '/basic-list/api/models', 0, 0, 0, 1, '2020-10-15 12:59:07', '2020-10-17 23:32:43', NULL),
(20, 19, 'add', '', '/basic-list/api/models/add', 1, 0, 0, 1, '2020-10-15 13:02:33', '2020-10-17 23:32:50', NULL),
(21, 19, 'edit', '', '/basic-list/api/models/:id', 1, 0, 0, 1, '2020-10-15 13:03:19', '2020-10-15 13:03:32', NULL),
(22, 19, 'design', '', '/basic-list/api/models/design/:id', 1, 0, 0, 1, '2020-10-15 13:06:00', '2020-10-15 13:55:11', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
