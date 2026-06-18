-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 26, 2025 at 09:01 PM
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
-- Database: `ioplantcare`
--

-- --------------------------------------------------------

--
-- Table structure for table `achievements`
--

CREATE TABLE `achievements` (
  `achievement_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `icon_class` varchar(50) NOT NULL DEFAULT 'bi-trophy',
  `criteria_code` varchar(50) NOT NULL,
  `xp_reward` int(11) NOT NULL DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `achievements`
--

INSERT INTO `achievements` (`achievement_id`, `title`, `description`, `icon_class`, `criteria_code`, `xp_reward`) VALUES
(1, 'New Beginnings', 'Create your account and join the community.', 'bi-person-check-fill', 'auth_register', 50),
(2, 'Green Thumb', 'Add your first plant to your collection.', 'bi-flower1', 'plant_add_first', 100),
(3, 'Sun Chaser', 'Record sunlight hours for a plant.', 'bi-brightness-high-fill', 'record_sun_first', 75),
(4, 'Hydration Hero', 'Water your first plant.', 'bi-droplet-fill', 'record_water_first', 75),
(10, 'Garden Starter', 'Own 5 different plants.', 'bi-collection', 'plant_count_5', 1500),
(11, 'Greenhouse Guru', 'Own 15 different plants.', 'bi-collection-fill', 'plant_count_15', 5000),
(12, 'Urban Jungle', 'Own 30 different plants.', 'bi-tree', 'plant_count_30', 15000),
(13, 'Botanical Legend', 'Own 50 different plants.', 'bi-tree-fill', 'plant_count_50', 40000),
(20, 'Watering Can', 'Water your plants 10 times.', 'bi-droplet', 'water_count_10', 500),
(21, 'Rainmaker', 'Water your plants 50 times.', 'bi-cloud-rain', 'water_count_50', 2500),
(22, 'Hydration Master', 'Water your plants 200 times.', 'bi-cloud-rain-fill', 'water_count_200', 10000),
(23, 'Poseidon', 'Water your plants 1000 times.', 'bi-tsunami', 'water_count_1000', 50000),
(30, 'Sun Bather', 'Record sunlight 10 times.', 'bi-brightness-alt-high', 'sun_count_10', 500),
(31, 'Solar Powered', 'Record sunlight 50 times.', 'bi-brightness-high', 'sun_count_50', 2500),
(32, 'Photosynthesis', 'Record sunlight 200 times.', 'bi-sun', 'sun_count_200', 10000),
(33, 'Sun God', 'Record sunlight 1000 times.', 'bi-sun-fill', 'sun_count_1000', 50000),
(40, 'Week Warrior', 'Log in for 7 days in a row.', 'bi-calendar-check', 'streak_7', 2000),
(41, 'Monthly Master', 'Log in for 30 days in a row.', 'bi-calendar-month', 'streak_30', 15000),
(42, 'Dedicated Planter', 'Log in for 100 days total.', 'bi-calendar3', 'login_total_100', 30000),
(50, 'Dear Diary', 'Write 5 journal entries for your plants.', 'bi-journal-text', 'journal_count_5', 1000),
(51, 'Plant Historian', 'Write 50 journal entries.', 'bi-journal-richtext', 'journal_count_50', 10000),
(60, 'Succulent Savant', 'Own 3 different types of Succulents or Cacti.', 'bi-flower3', 'col_succulent_3', 2500),
(61, 'Herb Hero', 'Own 5 different herbs.', 'bi-flower2', 'col_herb_5', 2500),
(62, 'Revivalist', 'Bring a plant from poor health back to excellent health.', 'bi-heart-pulse-fill', 'action_revive', 5000),
(999, 'The Great Pretender', '\"O Lord, have mercy on this Beast.\"', 'bi-incognito', 'special_demo_admin', 10000000);

-- --------------------------------------------------------

--
-- Table structure for table `bad_performance_alerts`
--

CREATE TABLE `bad_performance_alerts` (
  `alert_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_plant_id` int(11) NOT NULL,
  `alert_type` varchar(50) NOT NULL,
  `score` int(11) NOT NULL,
  `alert_time` datetime NOT NULL DEFAULT current_timestamp(),
  `handled` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bad_performance_alerts`
--

INSERT INTO `bad_performance_alerts` (`alert_id`, `user_id`, `user_plant_id`, `alert_type`, `score`, `alert_time`, `handled`) VALUES
(1, 9, 32, 'Water', 1, '2025-12-27 01:41:11', 1),
(2, 9, 32, 'Sunlight', 1, '2025-12-27 01:41:11', 1),
(3, 9, 32, 'Water', 1, '2025-12-27 01:47:30', 1),
(4, 9, 32, 'Sunlight', 1, '2025-12-27 01:47:30', 1),
(9, 9, 35, 'Water', 1, '2025-12-27 01:56:30', 1),
(10, 9, 35, 'Sunlight', 1, '2025-12-27 01:56:30', 1),
(11, 9, 36, 'Water', 1, '2025-12-27 01:58:53', 1),
(12, 9, 36, 'Sunlight', 1, '2025-12-27 01:58:53', 1),
(13, 9, 37, 'Water', 1, '2025-12-27 02:00:09', 0),
(14, 9, 37, 'Sunlight', 1, '2025-12-27 02:00:09', 1);

-- --------------------------------------------------------

--
-- Table structure for table `daily_status`
--

CREATE TABLE `daily_status` (
  `daily_status_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `water_score` int(11) DEFAULT 0,
  `sunlight_score` int(11) DEFAULT 0,
  `water_record_id` int(11) DEFAULT NULL,
  `sunlight_record_id` int(11) DEFAULT NULL,
  `user_plant_id` int(11) DEFAULT NULL,
  `plant_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_status`
--

INSERT INTO `daily_status` (`daily_status_id`, `user_id`, `date`, `water_score`, `sunlight_score`, `water_record_id`, `sunlight_record_id`, `user_plant_id`, `plant_id`) VALUES
(2, 10, '2025-12-26', 1, 1, NULL, NULL, 23, NULL),
(9, 10, '2025-12-26', 1, 1, NULL, NULL, 23, 96),
(10, 10, '2025-12-26', 1, 1, NULL, NULL, 22, 58),
(11, 10, '2025-12-26', 5, 5, 5, 6, 23, 96),
(12, 10, '2025-12-26', 1, 1, NULL, NULL, 22, 58),
(13, 10, '2025-12-26', 5, 5, 5, 6, 23, 96),
(16, 9, '2025-12-26', 1, 1, NULL, NULL, 25, 63),
(19, 9, '2025-12-26', 1, 1, NULL, NULL, 25, 63),
(22, 9, '2025-12-26', 3, 2, 8, 9, 25, 63),
(25, 9, '2025-12-26', 3, 2, 8, 9, 25, 63),
(28, 9, '2025-12-26', 3, 2, 8, 9, 25, 63),
(29, 14, '2025-12-26', 5, 1, 9, NULL, 26, 80),
(30, 14, '2025-12-26', 2, 1, 10, NULL, 27, 83),
(31, 14, '2025-12-26', 2, 1, 11, NULL, 28, 83),
(32, 14, '2025-12-26', 1, 1, NULL, NULL, 29, 55),
(33, 14, '2025-12-26', 5, 1, 9, NULL, 26, 80),
(34, 14, '2025-12-26', 2, 1, 10, NULL, 27, 83),
(35, 14, '2025-12-26', 2, 1, 11, NULL, 28, 83),
(36, 14, '2025-12-26', 1, 1, NULL, NULL, 29, 55),
(37, 14, '2025-12-26', 5, 1, 9, NULL, 26, 80),
(38, 14, '2025-12-26', 2, 1, 10, NULL, 27, 83),
(39, 14, '2025-12-26', 2, 1, 11, NULL, 28, 83),
(40, 14, '2025-12-26', 1, 1, NULL, NULL, 29, 55),
(41, 14, '2025-12-26', 5, 1, 9, NULL, 26, 80),
(42, 14, '2025-12-26', 2, 1, 10, NULL, 27, 83),
(43, 14, '2025-12-26', 2, 1, 11, NULL, 28, 83),
(44, 14, '2025-12-26', 1, 1, NULL, NULL, 29, 55),
(45, 14, '2025-12-26', 5, 1, 9, NULL, 26, 80),
(46, 14, '2025-12-26', 2, 1, 10, NULL, 27, 83),
(47, 14, '2025-12-26', 2, 1, 11, NULL, 28, 83),
(48, 14, '2025-12-26', 1, 1, NULL, NULL, 29, 55),
(49, 14, '2025-12-26', 5, 1, 9, NULL, 26, 80),
(50, 14, '2025-12-26', 2, 1, 10, NULL, 27, 83),
(51, 14, '2025-12-26', 2, 1, 11, NULL, 28, 83),
(52, 14, '2025-12-26', 1, 1, NULL, NULL, 29, 55),
(53, 14, '2025-12-26', 5, 1, 9, NULL, 26, 80),
(54, 14, '2025-12-26', 2, 1, 10, NULL, 27, 83),
(55, 14, '2025-12-26', 2, 1, 11, NULL, 28, 83),
(56, 14, '2025-12-26', 1, 1, NULL, NULL, 29, 55),
(57, 14, '2025-12-26', 5, 1, 9, NULL, 26, 80),
(58, 14, '2025-12-26', 2, 1, 10, NULL, 27, 83),
(59, 14, '2025-12-26', 2, 1, 11, NULL, 28, 83),
(60, 14, '2025-12-26', 1, 1, NULL, NULL, 29, 55),
(61, 14, '2025-12-26', 1, 1, 12, NULL, 30, 96),
(62, 14, '2025-12-26', 5, 1, 9, NULL, 26, 80),
(63, 14, '2025-12-26', 2, 1, 10, NULL, 27, 83),
(64, 14, '2025-12-26', 2, 1, 11, NULL, 28, 83),
(65, 14, '2025-12-26', 1, 1, NULL, NULL, 29, 55),
(66, 14, '2025-12-26', 1, 1, 12, NULL, 30, 96),
(67, 14, '2025-12-26', 5, 1, 9, 10, 26, 80),
(68, 14, '2025-12-26', 2, 1, 10, NULL, 27, 83),
(69, 14, '2025-12-26', 2, 1, 11, NULL, 28, 83),
(70, 14, '2025-12-26', 1, 1, NULL, NULL, 29, 55),
(71, 14, '2025-12-26', 1, 1, 12, NULL, 30, 96),
(72, 14, '2025-12-26', 5, 1, 9, 10, 26, 80),
(73, 14, '2025-12-26', 2, 1, 10, NULL, 27, 83),
(74, 14, '2025-12-26', 2, 1, 11, NULL, 28, 83),
(75, 14, '2025-12-26', 1, 1, NULL, NULL, 29, 55),
(76, 14, '2025-12-26', 1, 1, 12, NULL, 30, 96),
(77, 14, '2025-12-26', 5, 1, 9, 10, 26, 80),
(78, 14, '2025-12-26', 2, 1, 10, NULL, 27, 83),
(79, 14, '2025-12-26', 2, 1, 11, NULL, 28, 83),
(80, 14, '2025-12-26', 1, 1, NULL, NULL, 29, 55),
(81, 14, '2025-12-26', 1, 1, 12, NULL, 30, 96),
(82, 17, '2025-12-26', 1, 1, NULL, NULL, 31, 50),
(83, 17, '2025-12-26', 1, 1, NULL, NULL, 31, 50),
(85, 9, '2025-12-26', 3, 2, 8, 9, 25, 63),
(86, 9, '2025-12-26', 1, 1, NULL, NULL, 32, 80),
(88, 9, '2025-12-26', 3, 2, 8, 9, 25, 63),
(89, 9, '2025-12-26', 1, 1, NULL, NULL, 32, 80),
(91, 9, '2025-12-26', 3, 2, 8, 9, 25, 63),
(92, 9, '2025-12-26', 1, 1, NULL, NULL, 32, 80),
(94, 9, '2025-12-26', 3, 2, 8, 9, 25, 63),
(95, 9, '2025-12-26', 1, 1, NULL, NULL, 32, 80),
(97, 9, '2025-12-26', 3, 2, 8, 9, 25, 63),
(98, 9, '2025-12-26', 1, 1, NULL, NULL, 32, 80),
(100, 9, '2025-12-26', 3, 2, 8, 9, 25, 63),
(101, 9, '2025-12-26', 1, 1, NULL, NULL, 32, 80),
(102, 9, '2025-12-26', 5, 5, 13, 11, 33, 96),
(104, 9, '2025-12-26', 3, 2, 8, 9, 25, 63),
(105, 9, '2025-12-26', 1, 1, NULL, NULL, 32, 80),
(106, 9, '2025-12-26', 5, 5, 13, 11, 33, 96),
(108, 9, '2025-12-26', 3, 2, 8, 9, 25, 63),
(109, 9, '2025-12-26', 1, 1, NULL, NULL, 32, 80),
(110, 9, '2025-12-26', 5, 5, 13, 11, 33, 96),
(112, 9, '2025-12-26', 3, 2, 8, 9, 25, 63),
(113, 9, '2025-12-26', 5, 5, 14, 12, 32, 80),
(114, 9, '2025-12-26', 5, 5, 13, 11, 33, 96),
(117, 9, '2025-12-26', 3, 2, 8, 9, 25, 63),
(118, 9, '2025-12-26', 5, 5, 14, 12, 32, 80),
(119, 9, '2025-12-26', 5, 5, 13, 11, 33, 96),
(121, 9, '2025-12-26', 1, 1, NULL, NULL, 35, 55),
(122, 9, '2025-12-26', 3, 2, 8, 9, 25, 63),
(123, 9, '2025-12-26', 5, 5, 14, 12, 32, 80),
(124, 9, '2025-12-26', 5, 5, 13, 11, 33, 96),
(125, 9, '2025-12-26', 5, 3, 15, 13, 35, 55),
(126, 9, '2025-12-26', 1, 1, NULL, NULL, 36, 83),
(127, 9, '2025-12-26', 3, 2, 8, 9, 25, 63),
(128, 9, '2025-12-26', 5, 5, 14, 12, 32, 80),
(129, 9, '2025-12-26', 5, 5, 13, 11, 33, 96),
(130, 9, '2025-12-26', 5, 3, 15, 13, 35, 55),
(131, 9, '2025-12-26', 2, 3, 16, 14, 36, 83),
(132, 9, '2025-12-26', 3, 2, 8, 9, 25, 63),
(133, 9, '2025-12-26', 5, 5, 14, 12, 32, 80),
(134, 9, '2025-12-26', 5, 5, 13, 11, 33, 96),
(135, 9, '2025-12-26', 5, 3, 15, 13, 35, 55),
(136, 9, '2025-12-26', 2, 3, 16, 14, 36, 83),
(137, 9, '2025-12-26', 1, 1, 17, NULL, 37, 67);

--
-- Triggers `daily_status`
--
DELIMITER $$
CREATE TRIGGER `after_daily_status_check` AFTER INSERT ON `daily_status` FOR EACH ROW BEGIN
    -- Check Water Score
    IF NEW.water_score < 2 AND NEW.water_score > 0 THEN
        INSERT INTO bad_performance_alerts (user_id, user_plant_id, alert_type, score)
        VALUES (NEW.user_id, NEW.user_plant_id, 'Water', NEW.water_score);
    END IF;

    -- Check Sunlight Score
    IF NEW.sunlight_score < 2 AND NEW.sunlight_score > 0 THEN
        INSERT INTO bad_performance_alerts (user_id, user_plant_id, alert_type, score)
        VALUES (NEW.user_id, NEW.user_plant_id, 'Sunlight', NEW.sunlight_score);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `game_levels`
--

CREATE TABLE `game_levels` (
  `level_num` int(11) NOT NULL,
  `xp_required` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `game_levels`
--

INSERT INTO `game_levels` (`level_num`, `xp_required`) VALUES
(1, 0),
(2, 100),
(3, 250),
(4, 450),
(5, 700),
(6, 1000),
(7, 1350),
(8, 1750),
(9, 2200),
(10, 2700),
(11, 3300),
(12, 4000),
(13, 4800),
(14, 5700),
(15, 6700),
(16, 7800),
(17, 9000),
(18, 10300),
(19, 11700),
(20, 13200),
(30, 30000),
(40, 50000),
(50, 75000),
(60, 100000),
(70, 130000),
(80, 165000),
(90, 205000),
(100, 250000);

-- --------------------------------------------------------

--
-- Table structure for table `game_titles`
--

CREATE TABLE `game_titles` (
  `title_id` int(11) NOT NULL,
  `title_name` varchar(50) NOT NULL,
  `min_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `game_titles`
--

INSERT INTO `game_titles` (`title_id`, `title_name`, `min_level`) VALUES
(1, 'Novice Planter', 1),
(2, 'Sprout Scout', 10),
(3, 'Bloom Keeper', 20),
(4, 'Root Ranger', 30),
(5, 'Branch Baron', 40),
(6, 'Canopy King', 50),
(7, 'Forest Sage', 60),
(8, 'Nature Spirit', 70),
(9, 'Photosynthesis Pro', 80),
(10, 'Flora Legend', 90),
(11, 'Gaia Incarnate', 100);

-- --------------------------------------------------------

--
-- Table structure for table `plants`
--

CREATE TABLE `plants` (
  `plant_id` int(11) NOT NULL,
  `plant_name` varchar(50) DEFAULT NULL,
  `ideal_moisture` int(11) DEFAULT NULL,
  `ideal_sunlight` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plants`
--

INSERT INTO `plants` (`plant_id`, `plant_name`, `ideal_moisture`, `ideal_sunlight`) VALUES
(50, 'Aloe Vera', 40, 4),
(51, 'Snake Plant', 25, 2),
(52, 'Spider Plant', 60, 3),
(53, 'Peace Lily', 80, 3),
(54, 'Pothos', 50, 3),
(55, 'ZZ Plant', 20, 2),
(56, 'Jade Plant', 30, 4),
(57, 'Lucky Bamboo', 70, 2),
(58, 'Cactus', 20, 5),
(59, 'Succulent', 25, 5),
(60, 'Rubber Plant', 60, 4),
(61, 'Areca Palm', 90, 4),
(62, 'Boston Fern', 100, 3),
(63, 'Croton', 70, 5),
(64, 'Dracaena', 50, 3),
(65, 'Money Plant', 60, 3),
(66, 'Philodendron', 55, 3),
(67, 'Calathea', 85, 2),
(68, 'Fiddle Leaf Fig', 90, 5),
(69, 'Monstera', 80, 4),
(70, 'Chinese Evergreen', 50, 2),
(71, 'Anthurium', 75, 4),
(72, 'Dieffenbachia', 65, 3),
(73, 'Parlor Palm', 70, 3),
(74, 'Norfolk Pine', 85, 4),
(75, 'English Ivy', 60, 3),
(76, 'Prayer Plant', 80, 2),
(77, 'Schefflera', 70, 4),
(78, 'Kalanchoe', 30, 5),
(79, 'Begonia', 65, 3),
(80, 'African Violet', 50, 4),
(81, 'Coleus', 70, 4),
(82, 'Geranium', 60, 5),
(83, 'Hibiscus', 100, 6),
(84, 'Lavender', 30, 6),
(85, 'Rosemary', 35, 6),
(86, 'Mint', 80, 4),
(87, 'Basil', 70, 5),
(88, 'Thyme', 30, 6),
(89, 'Oregano', 35, 6),
(90, 'Coriander', 60, 4),
(91, 'Chives', 50, 4),
(92, 'Parsley', 60, 3),
(93, 'Spinach', 90, 3),
(94, 'Lettuce', 85, 3),
(95, 'Cilantro', 65, 4),
(96, 'Dill', 70, 5),
(97, 'Sage', 40, 6),
(98, 'Bay Leaf', 50, 5);

-- --------------------------------------------------------

--
-- Table structure for table `plant_care_tips`
--

CREATE TABLE `plant_care_tips` (
  `plant_tip_id` int(11) NOT NULL,
  `plant_id` int(11) NOT NULL,
  `user_plant_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plant_care_tips`
--

INSERT INTO `plant_care_tips` (`plant_tip_id`, `plant_id`, `user_plant_id`) VALUES
(1, 50, 31),
(2, 55, 29),
(3, 58, 22),
(5, 63, 25),
(7, 80, 26),
(8, 83, 27),
(9, 83, 28),
(10, 96, 23),
(11, 96, 30),
(16, 80, 32),
(17, 96, 33),
(19, 55, 35),
(20, 83, 36),
(21, 67, 37);

-- --------------------------------------------------------

--
-- Table structure for table `sunlight_records`
--

CREATE TABLE `sunlight_records` (
  `srecord_id` int(11) NOT NULL,
  `user_plant_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `calculated_samount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sunlight_records`
--

INSERT INTO `sunlight_records` (`srecord_id`, `user_plant_id`, `date`, `calculated_samount`) VALUES
(6, 23, '2025-12-26', 5),
(9, 25, '2025-12-26', 2),
(10, 26, '2025-12-26', 10),
(11, 33, '2025-12-26', 5),
(12, 32, '2025-12-26', 4),
(13, 35, '2025-12-26', 1),
(14, 36, '2025-12-26', 4),
(15, 37, '2025-12-26', 2);

--
-- Triggers `sunlight_records`
--
DELIMITER $$
CREATE TRIGGER `clear_sunlight_alert_after_insert` AFTER INSERT ON `sunlight_records` FOR EACH ROW BEGIN
    UPDATE bad_performance_alerts 
    SET handled = 1 
    WHERE user_plant_id = NEW.user_plant_id 
      AND alert_type = 'Sunlight' 
      AND handled = 0;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `creation_date` date NOT NULL,
  `username` varchar(15) DEFAULT NULL,
  `equipped_title` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `creation_date`, `username`, `equipped_title`) VALUES
(8, 'Nafisa Nawyar', 'nafisan7101@gmail.com', 'naf123', '2025-12-25', 'naf', NULL),
(9, 'Safwan Saad', 'saad@gmail.com', 'saad123', '2025-12-25', 'Saad02', 'Novice Planter'),
(10, 'NewUser', 'new@gmail.com', 'new123', '2025-12-25', 'New', NULL),
(14, 'Throne', 't@a.com', '123', '2025-12-26', 'Throne', 'Novice Planter'),
(17, 'System Admin', 'p@p.com', 'p', '2025-12-26', 'Pretender', 'Gaia Incarnate');

-- --------------------------------------------------------

--
-- Table structure for table `user_achievements`
--

CREATE TABLE `user_achievements` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `achievement_id` int(11) NOT NULL,
  `date_earned` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_achievements`
--

INSERT INTO `user_achievements` (`id`, `user_id`, `achievement_id`, `date_earned`) VALUES
(1, 14, 1, '2025-12-26 20:46:32'),
(2, 14, 2, '2025-12-26 20:46:32'),
(3, 14, 4, '2025-12-26 20:46:32'),
(4, 14, 3, '2025-12-26 22:08:52'),
(5, 17, 999, '2025-12-26 23:31:38'),
(6, 17, 1, '2025-12-27 00:01:33'),
(7, 17, 2, '2025-12-27 00:31:39'),
(8, 9, 1, '2025-12-27 01:01:00'),
(9, 9, 2, '2025-12-27 01:01:00'),
(10, 9, 3, '2025-12-27 01:01:00'),
(11, 9, 4, '2025-12-27 01:01:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_journal`
--

CREATE TABLE `user_journal` (
  `comment_id` int(11) NOT NULL,
  `user_plant_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment_text` text DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_journal`
--

INSERT INTO `user_journal` (`comment_id`, `user_plant_id`, `user_id`, `comment_text`, `date`) VALUES
(4, 22, 10, 'Day1: TOO MUCH SUNBATHE', '2025-12-26'),
(5, 25, 9, 'Day1: Perfectly hydrated + sunvathe', '2025-12-26'),
(7, 26, 14, 'toto', '2025-12-26'),
(8, 27, 14, 'Heal', '2025-12-26'),
(9, 28, 14, 'Alter', '2025-12-26'),
(10, 29, 14, 'ZZZ', '2025-12-26'),
(11, 29, 14, 'YYY', '2025-12-26'),
(12, 29, 14, 'A', '2025-12-26'),
(13, 32, 9, 'toto', '2025-12-27'),
(14, 25, 9, 'asd', '2025-12-27');

-- --------------------------------------------------------

--
-- Table structure for table `user_plant`
--

CREATE TABLE `user_plant` (
  `user_plant_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `user_plant` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_plant`
--

INSERT INTO `user_plant` (`user_plant_id`, `user_id`, `date`, `user_plant`) VALUES
(22, 10, '2025-12-25', 'Cactus'),
(23, 10, '2025-12-25', 'Dill'),
(25, 9, '2025-12-26', 'Croton'),
(26, 14, '2025-12-26', 'African Violet'),
(27, 14, '2025-12-26', 'Hibiscus'),
(28, 14, '2025-12-26', 'Hibiscus'),
(29, 14, '2025-12-26', 'ZZ Plant'),
(30, 14, '2025-12-26', 'Dill'),
(31, 17, '2025-12-26', 'Aloe Vera'),
(32, 9, '2025-12-26', 'African Violet'),
(33, 9, '2025-12-26', 'Dill'),
(35, 9, '2025-12-26', 'ZZ Plant'),
(36, 9, '2025-12-26', 'Hibiscus'),
(37, 9, '2025-12-26', 'Calathea');

--
-- Triggers `user_plant`
--
DELIMITER $$
CREATE TRIGGER `after_user_plant_insert` AFTER INSERT ON `user_plant` FOR EACH ROW BEGIN
    DECLARE v_plant_id INT;

    SELECT plant_id
    INTO v_plant_id
    FROM plants
    WHERE plant_name = NEW.user_plant
    LIMIT 1;

    IF v_plant_id IS NOT NULL THEN
        INSERT INTO plant_care_tips (plant_id, user_plant_id)
        VALUES (v_plant_id, NEW.user_plant_id);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `water_records`
--

CREATE TABLE `water_records` (
  `wrecord_id` int(11) NOT NULL,
  `user_plant_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `calculated_wamount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `water_records`
--

INSERT INTO `water_records` (`wrecord_id`, `user_plant_id`, `date`, `calculated_wamount`) VALUES
(5, 23, '2025-12-26', 70),
(8, 25, '2025-12-26', 50),
(9, 26, '2025-12-26', 50),
(10, 27, '2025-12-26', 20),
(11, 28, '2025-12-26', 1),
(12, 30, '2025-12-26', 1500),
(13, 33, '2025-12-26', 70),
(14, 32, '2025-12-26', 50),
(15, 35, '2025-12-26', 20),
(16, 36, '2025-12-26', 40),
(17, 37, '2025-12-26', 500);

--
-- Triggers `water_records`
--
DELIMITER $$
CREATE TRIGGER `clear_water_alert_after_insert` AFTER INSERT ON `water_records` FOR EACH ROW BEGIN
    UPDATE bad_performance_alerts 
    SET handled = 1 
    WHERE user_plant_id = NEW.user_plant_id 
      AND alert_type = 'Water' 
      AND handled = 0;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `achievements`
--
ALTER TABLE `achievements`
  ADD PRIMARY KEY (`achievement_id`);

--
-- Indexes for table `bad_performance_alerts`
--
ALTER TABLE `bad_performance_alerts`
  ADD PRIMARY KEY (`alert_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `user_plant_id` (`user_plant_id`);

--
-- Indexes for table `daily_status`
--
ALTER TABLE `daily_status`
  ADD PRIMARY KEY (`daily_status_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_daily_water` (`water_record_id`),
  ADD KEY `fk_daily_sun` (`sunlight_record_id`),
  ADD KEY `fk_userplant` (`user_plant_id`),
  ADD KEY `fk_plants` (`plant_id`);

--
-- Indexes for table `game_levels`
--
ALTER TABLE `game_levels`
  ADD PRIMARY KEY (`level_num`);

--
-- Indexes for table `game_titles`
--
ALTER TABLE `game_titles`
  ADD PRIMARY KEY (`title_id`);

--
-- Indexes for table `plants`
--
ALTER TABLE `plants`
  ADD PRIMARY KEY (`plant_id`);

--
-- Indexes for table `plant_care_tips`
--
ALTER TABLE `plant_care_tips`
  ADD PRIMARY KEY (`plant_tip_id`),
  ADD KEY `fk_pct_plant` (`plant_id`),
  ADD KEY `fk_pct_user_plant` (`user_plant_id`);

--
-- Indexes for table `sunlight_records`
--
ALTER TABLE `sunlight_records`
  ADD PRIMARY KEY (`srecord_id`),
  ADD KEY `user_plant_id` (`user_plant_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_achievements`
--
ALTER TABLE `user_achievements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_ach_unique` (`user_id`,`achievement_id`),
  ADD KEY `achievement_id` (`achievement_id`);

--
-- Indexes for table `user_journal`
--
ALTER TABLE `user_journal`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `user_plant_id` (`user_plant_id`),
  ADD KEY `fk_user_journal_user` (`user_id`);

--
-- Indexes for table `user_plant`
--
ALTER TABLE `user_plant`
  ADD PRIMARY KEY (`user_plant_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `water_records`
--
ALTER TABLE `water_records`
  ADD PRIMARY KEY (`wrecord_id`),
  ADD KEY `user_plant_id` (`user_plant_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `achievements`
--
ALTER TABLE `achievements`
  MODIFY `achievement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `bad_performance_alerts`
--
ALTER TABLE `bad_performance_alerts`
  MODIFY `alert_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `daily_status`
--
ALTER TABLE `daily_status`
  MODIFY `daily_status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT for table `game_titles`
--
ALTER TABLE `game_titles`
  MODIFY `title_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `plants`
--
ALTER TABLE `plants`
  MODIFY `plant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `plant_care_tips`
--
ALTER TABLE `plant_care_tips`
  MODIFY `plant_tip_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `sunlight_records`
--
ALTER TABLE `sunlight_records`
  MODIFY `srecord_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user_achievements`
--
ALTER TABLE `user_achievements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_journal`
--
ALTER TABLE `user_journal`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user_plant`
--
ALTER TABLE `user_plant`
  MODIFY `user_plant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `water_records`
--
ALTER TABLE `water_records`
  MODIFY `wrecord_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bad_performance_alerts`
--
ALTER TABLE `bad_performance_alerts`
  ADD CONSTRAINT `bad_performance_alerts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bad_performance_alerts_ibfk_2` FOREIGN KEY (`user_plant_id`) REFERENCES `user_plant` (`user_plant_id`) ON DELETE CASCADE;

--
-- Constraints for table `daily_status`
--
ALTER TABLE `daily_status`
  ADD CONSTRAINT `daily_status_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_daily_sun` FOREIGN KEY (`sunlight_record_id`) REFERENCES `sunlight_records` (`srecord_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_daily_water` FOREIGN KEY (`water_record_id`) REFERENCES `water_records` (`wrecord_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_plants` FOREIGN KEY (`plant_id`) REFERENCES `plants` (`plant_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_userplant` FOREIGN KEY (`user_plant_id`) REFERENCES `user_plant` (`user_plant_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `plant_care_tips`
--
ALTER TABLE `plant_care_tips`
  ADD CONSTRAINT `fk_pct_plant` FOREIGN KEY (`plant_id`) REFERENCES `plants` (`plant_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pct_user_plant` FOREIGN KEY (`user_plant_id`) REFERENCES `user_plant` (`user_plant_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sunlight_records`
--
ALTER TABLE `sunlight_records`
  ADD CONSTRAINT `sunlight_records_ibfk_1` FOREIGN KEY (`user_plant_id`) REFERENCES `user_plant` (`user_plant_id`);

--
-- Constraints for table `user_achievements`
--
ALTER TABLE `user_achievements`
  ADD CONSTRAINT `user_achievements_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_achievements_ibfk_2` FOREIGN KEY (`achievement_id`) REFERENCES `achievements` (`achievement_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_journal`
--
ALTER TABLE `user_journal`
  ADD CONSTRAINT `fk_user_journal_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_journal_ibfk_1` FOREIGN KEY (`user_plant_id`) REFERENCES `user_plant` (`user_plant_id`);

--
-- Constraints for table `user_plant`
--
ALTER TABLE `user_plant`
  ADD CONSTRAINT `user_plant_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `water_records`
--
ALTER TABLE `water_records`
  ADD CONSTRAINT `water_records_ibfk_1` FOREIGN KEY (`user_plant_id`) REFERENCES `user_plant` (`user_plant_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
