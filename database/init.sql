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

INSERT INTO `auth_admin_group` (`id`, `admin_id`, `group_id`, `create_time`, `update_time`, `delete_time`, `status`) VALUES
(225, 1, 53, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, 1);

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

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`version`, `migration_name`, `start_time`, `end_time`, `breakpoint`) VALUES
(20190807040934, 'Admin', '2019-08-07 16:40:31', '2019-08-07 16:40:31', 0),
(20190807161822, 'AdminInsert1', '2019-08-07 16:40:31', '2019-08-07 16:40:31', 0),
(20190816070413, 'AdminGroupBasic', '2019-08-16 07:13:57', '2019-08-16 07:13:57', 0),
(20190816162543, 'RuleBasic', '2019-08-16 16:39:06', '2019-08-16 16:39:06', 0),
(20190817040446, 'AdminGroup', '2019-08-17 04:26:48', '2019-08-17 04:26:48', 0),
(20190817044927, 'AdminGroupDemo1', '2019-08-17 04:54:58', '2019-08-17 04:54:58', 0),
(20190819092247, 'GroupParentId', '2019-08-19 09:28:05', '2019-08-19 09:28:05', 0),
(20190902143256, 'RuleParentId', '2019-09-02 14:34:10', '2019-09-02 14:34:10', 0),
(20190911075231, 'AddIsMenu', '2019-09-11 07:56:20', '2019-09-11 07:56:20', 0),
(20200908153058, 'DbMigrationsRemoveGroupIndexName', '2020-09-08 15:56:05', '2020-09-08 15:56:05', 0),
(20200914160452, 'DbMigrationsGroupTableFix', '2020-09-14 16:14:54', '2020-09-14 16:14:54', 0),
(20200914161648, 'DbMigrationsAuthGroupRule', '2020-09-14 16:20:36', '2020-09-14 16:20:36', 0),
(20200929080709, 'DbMigrationsRemoveRuleUnique', '2020-09-29 08:09:35', '2020-09-29 08:09:35', 0),
(20200930075518, 'DbMigrationsCreateTableModel', '2020-09-30 08:13:08', '2020-09-30 08:13:08', 0),
(20200930084857, 'DbMigrationsAddModelTableData', '2020-09-30 10:10:58', '2020-09-30 10:10:58', 0),
(20200930155341, 'DbMigrationsAddModelTitle', '2020-09-30 15:57:16', '2020-09-30 15:57:16', 0),
(20201005140105, 'DbMigrationsAddModelTime', '2020-10-05 14:24:42', '2020-10-05 14:24:42', 0),
(20201006145835, 'DbMigrationsRemoveModelFields', '2020-10-06 15:09:24', '2020-10-06 15:09:24', 0),
(20201013063118, 'DbMigrationsRefactorRuleMenu', '2020-10-13 06:57:34', '2020-10-13 06:57:34', 0),
(20201013074626, 'DbMigrationsAddMenuName', '2020-10-13 10:36:00', '2020-10-13 10:36:00', 0),
(20201013152215, 'DbMigrationsMenuTable', '2020-10-13 15:36:37', '2020-10-13 15:36:37', 0),
(20201013153817, 'DbMigrationsRemoveRuleMenu', '2020-10-13 15:41:58', '2020-10-13 15:41:58', 0),
(20210121064211, 'DbMigrationsAddRouteInModelTable', '2021-04-09 05:48:48', '2021-04-09 05:48:48', 0),
(20210211072732, 'DbMigrationsChangeAppName', '2021-04-09 05:48:48', '2021-04-09 05:48:48', 0),
(20210409051717, 'DbMigrationsUnifiedFieldName', '2021-04-09 05:48:48', '2021-04-09 05:48:48', 0),
(20210413050513, 'DbMigrationsDeleteAdmin', '2021-04-13 05:13:08', '2021-04-13 05:13:08', 0),
(20210413071208, 'Test', '2021-04-13 01:12:08', '2021-04-13 01:12:08', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
