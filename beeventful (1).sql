-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2024 at 08:04 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `beeventful`
--

-- --------------------------------------------------------

--
-- Table structure for table `chatbot`
--

CREATE TABLE `chatbot` (
  `id` int(11) NOT NULL,
  `queries` varchar(250) DEFAULT NULL,
  `replies` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chatbot`
--

INSERT INTO `chatbot` (`id`, `queries`, `replies`) VALUES
(1, 'Hi,Hello,hiii,hola,Hello there,hey,hiiii', 'Hi! I am Be\'eventful bot'),
(2, 'Good morning,morning', 'Good morning'),
(3, 'Good Night,night', 'Good night'),
(4, 'Good evening,evening', 'Good evening'),
(5, 'Good afternoon,noon,afternoon', 'Good afternoon'),
(6, 'pending,my order,order', 'query_order'),
(7, 'Bye,Good bye,see you', 'Ok! see you later'),
(8, 'how are you,what\'s up', 'I\'m good! and you?'),
(9, 'I am fine, i\'m fine', 'Hmm, how can i help you?');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL,
  `incoming_msg_id` int(255) NOT NULL,
  `outgoing_msg_id` int(255) NOT NULL,
  `msg` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` time NOT NULL DEFAULT current_timestamp(),
  `msg_date` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`msg_id`, `incoming_msg_id`, `outgoing_msg_id`, `msg`, `time`, `msg_date`) VALUES
(1, 1156741392, 1011174066, 'Hey', '10:10:10', '2024-03-06'),
(2, 1011174066, 1156741392, 'Heyy', '10:10:12', '2024-03-07'),
(3, 1156741392, 1011174066, 'What you doing?', '10:10:10', '2024-03-08'),
(4, 1550096251, 927004138, 'Hey', '10:17:28', '2024-03-14'),
(5, 9, 9, 'hihihihi', '12:23:24', '2024-03-14'),
(6, 31, 9, 'hi', '12:37:41', '2024-03-12'),
(7, 31, 9, 'How you doing??', '10:10:10', '2024-03-13'),
(8, 9, 31, 'I\'m Good wau??', '10:10:12', '2024-03-14'),
(9, 31, 9, '😘', '00:00:00', '2024-03-14'),
(10, 34, 9, 'Hey😂', '10:20:08', '2024-03-21'),
(11, 4, 9, 'Hey😊', '09:38:51', '2024-04-02');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_booking`
--

CREATE TABLE `tbl_booking` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `from_time` time NOT NULL,
  `to_time` time NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `booking_status` tinyint(1) DEFAULT 1,
  `total_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_booking`
--

INSERT INTO `tbl_booking` (`booking_id`, `user_id`, `start_date`, `end_date`, `from_time`, `to_time`, `booking_date`, `booking_status`, `total_amount`) VALUES
(1, 3, '2024-04-01', '2024-01-09', '10:00:00', '12:00:00', '2024-03-29 02:30:00', 1, '5000.00'),
(3, 6, '2024-03-29', '2024-03-12', '09:00:00', '11:00:00', '2024-03-30 06:15:00', 1, '7000.00'),
(4, 13, '2024-03-30', '2024-04-17', '13:00:00', '15:00:00', '2024-03-28 09:50:00', 1, '5500.00'),
(5, 21, '2024-04-02', '2024-05-22', '11:00:00', '13:00:00', '2024-04-01 04:15:00', 1, '6200.00'),
(6, 3, '2024-06-25', '2024-06-27', '10:30:00', '12:30:00', '2024-03-27 06:40:00', 1, '6800.00'),
(7, 4, '2024-07-10', '2024-07-12', '08:00:00', '10:00:00', '2024-04-02 02:25:00', 1, '7200.00'),
(8, 6, '2024-08-08', '2024-08-10', '15:00:00', '17:00:00', '2024-03-12 04:00:00', 1, '5800.00'),
(9, 13, '2024-09-14', '2024-09-16', '12:00:00', '14:00:00', '2024-03-15 04:50:00', 1, '6900.00'),
(10, 21, '2024-10-19', '2024-10-21', '09:30:00', '11:30:00', '2024-03-09 02:45:00', 1, '7300.00'),
(38, 9, '2024-03-08', '2024-03-10', '12:00:00', '12:00:00', '2024-02-06 15:38:03', 1, '8400.00'),
(39, 3, '2024-02-15', '2024-02-17', '12:02:17', '18:02:17', '2024-01-14 14:35:17', 1, '5600.00'),
(40, 6, '2024-03-13', '2024-03-15', '20:06:27', '20:06:12', '2024-03-14 14:39:20', 1, '4000.00'),
(41, 6, '2024-03-03', '2024-03-05', '20:06:20', '10:06:25', '2024-03-14 14:39:20', 1, '3500.00'),
(52, 4, '2023-02-05', '2023-02-07', '14:00:00', '16:00:00', '2024-03-15 05:00:00', 1, '6000.00'),
(54, 9, '2024-03-19', '2024-03-21', '00:00:00', '00:00:00', '2024-03-18 15:15:54', 1, '9000.00'),
(55, 9, '2024-04-03', '2024-04-05', '00:00:00', '00:00:00', '2024-04-01 15:30:58', 1, '10000.00'),
(56, 3, '2017-03-15', '2017-03-16', '10:00:00', '12:00:00', '2017-03-09 18:30:00', 1, '100.00'),
(57, 6, '2017-04-20', '2017-04-22', '09:00:00', '11:00:00', '2017-03-14 18:30:00', 1, '150.00'),
(58, 9, '2017-05-05', '2017-05-07', '14:00:00', '16:00:00', '2017-02-28 18:30:00', 1, '200.00'),
(59, 13, '2018-03-15', '2018-03-16', '10:00:00', '12:00:00', '2018-03-09 18:30:00', 1, '100.00'),
(60, 16, '2018-04-20', '2018-04-22', '09:00:00', '11:00:00', '2018-03-14 18:30:00', 1, '150.00'),
(61, 21, '2018-05-05', '2018-05-07', '14:00:00', '16:00:00', '2018-02-28 18:30:00', 1, '200.00'),
(62, 3, '2019-03-15', '2019-03-16', '10:00:00', '12:00:00', '2019-03-09 18:30:00', 1, '100.00'),
(63, 6, '2019-04-20', '2019-04-22', '09:00:00', '11:00:00', '2019-03-14 18:30:00', 1, '150.00'),
(64, 9, '2019-05-05', '2019-05-07', '14:00:00', '16:00:00', '2019-02-28 18:30:00', 1, '200.00'),
(65, 13, '2020-03-15', '2020-03-16', '10:00:00', '12:00:00', '2020-03-09 18:30:00', 1, '100.00'),
(66, 16, '2020-04-20', '2020-04-22', '09:00:00', '11:00:00', '2020-03-14 18:30:00', 1, '150.00'),
(67, 21, '2020-05-05', '2020-05-07', '14:00:00', '16:00:00', '2020-02-29 18:30:00', 1, '200.00'),
(68, 3, '2021-03-15', '2021-03-16', '10:00:00', '12:00:00', '2021-03-09 18:30:00', 1, '100.00'),
(69, 6, '2021-04-20', '2021-04-22', '09:00:00', '11:00:00', '2021-03-14 18:30:00', 1, '150.00'),
(70, 9, '2021-05-05', '2021-05-07', '14:00:00', '16:00:00', '2021-02-28 18:30:00', 1, '200.00'),
(71, 13, '2022-03-15', '2022-03-16', '10:00:00', '12:00:00', '2022-03-09 18:30:00', 1, '100.00'),
(72, 16, '2022-04-20', '2022-04-22', '09:00:00', '11:00:00', '2022-03-14 18:30:00', 1, '150.00'),
(73, 21, '2022-05-05', '2022-05-07', '14:00:00', '16:00:00', '2022-02-28 18:30:00', 1, '200.00'),
(74, 3, '2023-03-15', '2023-03-16', '10:00:00', '12:00:00', '2023-03-09 18:30:00', 1, '100.00'),
(75, 6, '2023-04-20', '2023-04-22', '09:00:00', '11:00:00', '2023-03-14 18:30:00', 1, '150.00'),
(76, 9, '2023-05-05', '2023-05-07', '14:00:00', '16:00:00', '2023-02-28 18:30:00', 1, '200.00'),
(77, 13, '2024-03-15', '2024-03-16', '10:00:00', '12:00:00', '2024-03-09 18:30:00', 1, '100.00'),
(78, 16, '2024-04-20', '2024-04-22', '09:00:00', '11:00:00', '2024-03-14 18:30:00', 1, '150.00'),
(79, 21, '2024-05-05', '2024-05-07', '14:00:00', '16:00:00', '2024-02-29 18:30:00', 1, '200.00'),
(80, 9, '2024-07-03', '2024-07-04', '00:00:00', '00:00:00', '2024-07-03 13:49:05', 1, '2800.00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cart_service`
--

CREATE TABLE `tbl_cart_service` (
  `user_id` int(11) NOT NULL,
  `venue_service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cart_venue`
--

CREATE TABLE `tbl_cart_venue` (
  `venue_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer_payment`
--

CREATE TABLE `tbl_customer_payment` (
  `id` int(11) NOT NULL,
  `payment_id` varchar(512) DEFAULT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL,
  `vendor_status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_customer_payment`
--

INSERT INTO `tbl_customer_payment` (`id`, `payment_id`, `booking_id`, `datetime`, `total_amount`, `vendor_status`) VALUES
(5, 'pay_dhruvish16', 3, '2024-03-17 18:25:13', '4500.00', 1),
(6, 'payment_id2608', 4, '2024-03-17 18:25:13', '50000.00', 1),
(25, 'pay_id565202616', 6, '2024-03-17 18:24:08', '5600.00', 1),
(30, 'pay_NXe6xu4YaxoJXg', 52, '2024-02-06 21:08:03', '8400.00', 1),
(31, 'pay_OUBJhLgUH5lSWv', 80, '2024-07-03 19:19:05', '2800.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_meeting_requests`
--

CREATE TABLE `tbl_meeting_requests` (
  `request_id` int(11) NOT NULL,
  `venue_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `meeting_date` date DEFAULT NULL,
  `meeting_time` time DEFAULT NULL,
  `meeting_agenda` text DEFAULT NULL,
  `status` tinyint(2) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_meeting_requests`
--

INSERT INTO `tbl_meeting_requests` (`request_id`, `venue_id`, `user_id`, `meeting_date`, `meeting_time`, `meeting_agenda`, `status`, `created_at`) VALUES
(1, 1, 9, '2024-03-05', '10:09:00', 'Venue', 1, '2024-03-03 14:34:00'),
(2, 1, 9, '2024-03-16', '03:44:00', 'Venue Related Queries', 1, '2024-03-08 03:42:06'),
(4, 4, 9, '2024-04-01', '20:55:00', 'Want to know more regarding venue\'s details.', 1, '2024-04-01 15:21:50'),
(5, 1, 9, '2024-04-08', '22:48:00', 'Just ask about the plot', 1, '2024-04-08 17:17:23'),
(6, 1, 9, '2024-04-08', '22:48:00', 'Just ask about the plot', 1, '2024-04-08 17:18:12'),
(7, 1, 9, '2024-04-08', '22:48:00', 'Just ask about the plot', 1, '2024-04-08 17:18:33'),
(8, 1, 9, '2024-04-08', '22:48:00', 'Just ask about the plot', 1, '2024-04-08 17:18:38'),
(9, 1, 9, '2024-04-08', '22:48:00', 'Just ask about the plot', 1, '2024-04-08 17:40:19'),
(10, 1, 9, '2024-04-08', '22:48:00', 'Just ask about the plot', 1, '2024-04-08 17:40:48');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_messages`
--

CREATE TABLE `tbl_messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message_text` text NOT NULL,
  `sent_time` datetime NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_messages`
--

INSERT INTO `tbl_messages` (`message_id`, `sender_id`, `receiver_id`, `message_text`, `sent_time`, `is_read`) VALUES
(1, 1, 9, 'Hello', '2024-04-07 21:31:02', 1),
(2, 1, 9, 'What are you doing?', '2024-04-06 21:31:02', 1),
(3, 9, 1, 'Nothing', '2024-04-07 21:31:33', 1),
(4, 1, 9, 'Hello', '2024-04-07 21:31:33', 1),
(5, 1, 2, 'Hiii', '2024-04-07 21:32:47', 0),
(6, 9, 1, 'Hii', '2024-04-07 22:10:54', 1),
(7, 9, 1, 'Hello', '2024-04-07 22:11:41', 1),
(8, 9, 1, 'K', '2024-04-07 22:11:56', 1),
(9, 9, 1, 'Hii', '2024-04-07 22:13:09', 1),
(10, 9, 2, 'Hello', '2024-04-07 22:13:21', 1),
(11, 9, 1, 'Hello', '2024-04-08 11:31:13', 1),
(12, 1, 9, 'Hiiiiiiiiiiiiiiiiiiii', '2024-04-08 11:33:09', 1),
(13, 1, 9, 'Okk', '2024-04-08 11:33:16', 1),
(19, 9, 1, 'Hello', '2024-04-08 12:11:51', 1),
(20, 1, 9, 'hoo', '2024-04-08 12:12:32', 1),
(21, 1, 9, 'Hii', '2024-04-08 12:12:45', 1),
(22, 9, 1, 'Hello', '2024-04-08 12:13:50', 1),
(23, 9, 1, 'Hii', '2024-04-08 12:15:17', 1),
(24, 9, 1, 'Yoo', '2024-04-08 12:15:48', 1),
(25, 1, 9, 'Hiii', '2024-04-08 12:17:12', 1),
(26, 9, 1, 'Hii', '2024-04-08 12:17:26', 1),
(27, 9, 1, 'Yoo', '2024-04-08 12:17:38', 1),
(28, 1, 9, 'Hii😊', '2024-04-08 12:19:40', 1),
(29, 1, 9, 'Hey 😒💕', '2024-04-08 12:20:37', 1),
(30, 9, 1, 'hiiiiiiiiiii', '2024-04-08 12:20:57', 1),
(31, 9, 1, 'Hiiiiiii', '2024-04-08 17:15:10', 1),
(32, 34, 9, 'Hey', '2024-04-08 20:56:01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_scheduled_meetings`
--

CREATE TABLE `tbl_scheduled_meetings` (
  `request_id` int(11) NOT NULL,
  `meeting_date` date NOT NULL,
  `meeting_time` time NOT NULL,
  `room_code` varchar(20) NOT NULL,
  `user_status` tinyint(1) NOT NULL DEFAULT 1,
  `vendor_status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_scheduled_meetings`
--

INSERT INTO `tbl_scheduled_meetings` (`request_id`, `meeting_date`, `meeting_time`, `room_code`, `user_status`, `vendor_status`) VALUES
(1, '2024-03-09', '20:25:00', 'KPYZHBWKIZMDEBARBFLX', 0, 0),
(2, '2024-03-14', '00:00:00', 'AFXPZHRSYMTOOQZEDXPY', 0, 0),
(4, '2024-04-01', '21:00:00', 'TGHVGFCHNAWUOCDKGFOY', 0, 0),
(5, '2024-07-03', '19:28:00', 'QJZYTGZBDVRLVRZAJTYR', 0, 0),
(6, '2024-07-03', '20:27:00', 'CWXDFFJCXEHANFHBPTGO', 1, 1),
(7, '2024-07-03', '20:40:00', 'FVWCPRPGEIESLINCSBCI', 1, 1),
(8, '2024-07-03', '19:36:00', 'FQYTCACQFOZHZFRUHNXT', 0, 0),
(9, '2024-07-03', '19:40:00', 'MQSGOIOYZTKAVVXOAWXT', 1, 1),
(10, '2024-07-03', '19:46:00', 'ZCXEFRPIGTZQSEKFVOEE', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_service`
--

CREATE TABLE `tbl_service` (
  `service_id` int(11) NOT NULL,
  `service_name` varchar(50) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_service`
--

INSERT INTO `tbl_service` (`service_id`, `service_name`, `status`) VALUES
(1, 'Music', 0),
(2, 'Decoration', 1),
(3, 'Servents', 1),
(4, 'Chairs', 1),
(7, 'Rooms', 1),
(8, 'Photography/Videography', 1),
(9, 'Cateringss', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_service_book`
--

CREATE TABLE `tbl_service_book` (
  `booking_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_service_book`
--

INSERT INTO `tbl_service_book` (`booking_id`, `service_id`) VALUES
(38, 135),
(38, 136),
(80, 135),
(80, 136);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `contact` char(10) NOT NULL,
  `user_type` char(1) DEFAULT 'C',
  `status` int(11) DEFAULT 1,
  `chat_status` char(30) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `Address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `name`, `email`, `password`, `contact`, `user_type`, `status`, `chat_status`, `date`, `Address`) VALUES
(1, 'Natsha Romanoff', 'ayshamulla9313@gmail.com', '$2y$10$llVLT8xXfcSXdlILN8JZpegR5V1kR8W58sVLhKjQk5REG/3R8vEXW', '8734907868', 'V', 1, 'Offline now', '2023-11-13', 'Chikhli, Valsad'),
(2, 'Dhruvi Mistry', 'mistry@gmail.com', '$2y$10$llVLT8xXfcSXdlILN8JZpegR5V1kR8W58sVLhKjQk5REG/3R8vEXW', '8956235681', 'V', 1, 'Offline now', '2023-11-13', 'Navsari'),
(3, 'Hardik Parmar', 'hardik@gmail.com', '$2y$10$llVLT8xXfcSXdlILN8JZpegR5V1kR8W58sVLhKjQk5REG/3R8vEXW', '8956235680', 'C', 1, 'Offline now', '2023-11-13', 'Surat'),
(4, 'Dharmesh Patel', 'dharmesh@gmail.com', '$2y$10$llVLT8xXfcSXdlILN8JZpegR5V1kR8W58sVLhKjQk5REG/3R8vEXW', '8956235640', 'V', 1, 'Offline now', '2023-11-13', 'Eru, Navsari'),
(5, 'hiral naik', 'hiral@gmail.com', '$2y$10$llVLT8xXfcSXdlILN8JZpegR5V1kR8W58sVLhKjQk5REG/3R8vEXW', '8956235683', 'A', 1, 'Offline now', '2023-11-13', ''),
(6, 'Drasti Desai', 'drasti@gmail.com', '$2y$10$j2VLmM.7GQv5qpMWDoAnMOPaoUNrmj.VS0TDGVdjl9vhDfGEB.pO.', '9624409050', 'C', 1, 'Offline now', '2023-11-13', ''),
(7, 'Himesh Sonvane', 'himesh@gmail.com', '$2y$10$gy5hrYgRhn9Vi/dJ.nRF8eMkJhXigGJIm8MvGGOD2oK/syp.qCEdC', '8140148544', 'V', 1, 'Offline now', '2023-11-13', ''),
(8, 'dhruvi', 'dhruvi@gmail.com', '$2y$10$mnlcphocwTf463Pa7b5S/u/Dx5yl5j.WzS7C9lubapbe.XImYEd7G', '9909262834', 'V', 1, 'Offline now', '2023-11-13', ''),
(9, 'priyank', 'parthdadawala26@gmail.com', '$2y$10$18.eP1bfI9spA8sc3X15pefTmcg5kjquC9ZegHTnY0cCvnIiQS4Da', '9909962839', 'C', 1, 'Active now', '2023-11-13', 'Kamrej,Surat'),
(10, 'Gehna ', 'gehna@gmail.com', '$2y$10$5mATEds5yqzvocpdr5itDOLsE6TrkYGT0FBwnfj.5MaZcaKnZ4T/K', '9909562839', 'V', 1, 'Offline now', '2023-11-13', ''),
(13, 'mahek', 'mahek@gmail.com', '$2y$10$nQABKIgkEQ6Y1EyrNFsNz.a/gEjY/Pbz/jLszej.ZDm1kZGEEoxj.', '9909867553', 'C', 1, 'Offline now', '2023-11-13', ''),
(16, 'Prakash', 'officialprakash283@gmail.com', '$2y$10$LJXdHJ.wI3OeRX6H9cS2zeNDFlXYizPpcukrKYVLj/gf8ueH0YpBS', '7383308167', 'C', 1, 'Offline now', '2023-11-13', ''),
(21, 'mahek', 'mahekpatel752@gmail.com', '$2y$10$2mfxE8ZXoEyImL7QThKEQuyOm1JQVtc21n.tAkxIJRyOtjcfKLrm.', '9537879585', 'C', 1, 'Offline now', '2023-11-13', ''),
(24, 'Aryan', 'aryandesai@gmail.com', '$2y$10$Mf0iTZ4eUrlw..ZfxZdeLe6nijjffOrx4XKnPRs4W9kInn17Khj0a', '9687478873', 'V', 1, 'Offline now', '2023-11-30', ''),
(25, 'Arth', 'arthpatel@gmail.com', '$2y$10$dEe7GwRRwKjvG5OUgDY6euBnNO7rDaT8.d/X2stjiNtjYawO81Wte', '9876543215', 'V', 1, 'Offline now', '2023-11-30', ''),
(26, 'Stavan', 'stavan@gmail.com', '$2y$10$VRU7fSziidhHQlyMIwaHg.gM0/4.MfD3JL/yIrVANbfamK9RpFuW6', '6356789123', 'V', 1, 'Offline now', '2023-12-09', ''),
(27, 'MEgha', 'megha@gmail.com', '$2y$10$mAVnsUc6oe6MagEZKqQjWOZft/2UHJVlAl8sFCkzBvBYFX0CcMa4O', '7456764345', 'V', 1, 'Offline now', '2023-12-09', ''),
(28, 'Chetna', 'chetna@gmail.com', '$2y$10$FnsOu2tMhTstT2GJN6KvaOif1s5k1dPmlZmkJrVSDSPo.VfiHfWEi', '6789345673', 'V', 1, 'Offline now', '2023-12-09', ''),
(29, 'Urvashi', 'urvashi@gmail.com', '$2y$10$./AA94dHa9y8ZzGveUrN1Od8/0.cy16lh29HkaO5VMyhCNttOf.hi', '9987865467', 'V', 1, 'Offline now', '2023-12-09', ''),
(31, 'Shakti', 'kmdesai410@gmail.com', '$2y$10$2UfFkj6AcVLa50XUMzAOKulj76dkf7DZHtoARGOiaklN3RCwwswGq', '8765432345', 'V', 1, 'Offline now', '2023-12-09', ''),
(32, 'dhruvi', 'dhruvinaik@gmail.com', '$2y$10$GUz/JA.N1vOpe3COkFGh4uXVM1JgqWlO6mB9SaeeMoS9iYr8vW5HG', '9537879580', 'C', 1, 'Offline now', '2023-12-09', ''),
(33, 'kabir', 'kabir.sheth747@gmail.com', '$2y$10$WmK3wfS.Eqg1xbSZ2oOitevSm6iLx4cjzAzGTyqEMdhKI8X.j5G3C', '9913124211', 'C', 1, 'Offline now', '2024-01-11', ''),
(34, 'Sangita', 'sangi.sakhi@gmail.com', '$2y$10$MWZILwFj6dupuUprK8/ys.F.RXIYgCXl.VAQCpqxj0kqVXVzScf7.', '9427111932', 'V', 1, 'Offline now', '2024-03-14', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_vendor_payment`
--

CREATE TABLE `tbl_vendor_payment` (
  `vendor_pay_id` int(11) NOT NULL,
  `payment_id` varchar(100) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `date_time` datetime NOT NULL DEFAULT current_timestamp(),
  `booking_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_vendor_payment`
--

INSERT INTO `tbl_vendor_payment` (`vendor_pay_id`, `payment_id`, `vendor_id`, `date_time`, `booking_id`, `total_amount`) VALUES
(1, 'pay_N9wYbXSZjF1djI', 1, '2023-12-08 12:00:00', 38, '3200.00'),
(2, 'pay_N9wZ58fiQ8jEQr', 1, '2023-12-08 12:00:00', 39, '7200.00'),
(3, 'pay_NA6zLmmSK30Pod', 1, '2023-12-09 12:00:00', 40, '60000.00'),
(4, 'pay_NtQtu5vGMkGZb3', 52, '2024-04-01 22:30:41', 1, '6720.00'),
(5, 'pay_OUBMhYbC9u7O1V', 80, '2024-07-03 19:21:56', 1, '2240.00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_venue`
--

CREATE TABLE `tbl_venue` (
  `venue_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(200) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `capacity` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `status` tinyint(4) DEFAULT 1,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_venue`
--

INSERT INTO `tbl_venue` (`venue_id`, `name`, `address`, `price`, `capacity`, `vendor_id`, `status`, `date`) VALUES
(1, 'Radhe-Krishna Party Plot', 'Valsad, Gujarat', '1400.00', 1000, 1, 1, '2023-11-11 00:00:00'),
(4, 'Eden Gardens and Resorts', 'Eru Navsari', '5000.00', 100, 4, 1, '2023-11-11 00:00:00'),
(20, 'S G Party Plot', 'Navsarii', '3000.00', 2300, 10, 0, '2023-11-11 00:00:00'),
(22, 'Aisha', 'Navsari', '2000.00', 3000, 10, 0, '2023-11-11 00:00:00'),
(27, 'Gehnaas plottt', 'vapi', '4500.00', 450, 10, 0, '2023-11-11 00:00:00'),
(33, 'Twinkie', 'Chikhlii', '25000.00', 230, 10, 1, '2023-11-11 00:00:00'),
(36, 'lions den', 'navsarii', '2300.00', 350, 10, 1, '2023-11-11 00:00:00'),
(63, 'Shreeji Party plot', 'Besides Rajhans colony, Near canal road, pal surat', '50000.00', 5000, 1, 1, '2023-11-30 00:00:00'),
(64, 'Vijya-Lakshmii ', 'Near Bhulka-Bhavan School, Besides shreeji arcade,', '45000.00', 3000, 1, 1, '2023-11-30 00:00:00'),
(65, 'B R farm', 'Near 7 /11 patrolpump, jamalpore,surat', '300000.00', 10000, 24, 0, '2023-11-30 00:00:00'),
(66, 'Radhe Farm', 'Villege Vav Kamrej ,surat', '50000.00', 500, 26, 1, '2023-12-09 00:00:00'),
(67, 'Govindji Hall', 'Kusum Park , Surat', '30000.00', 3500, 26, 0, '2023-12-09 00:00:00'),
(68, 'Park Celebration hall', 'Vip road vesu, surat', '70000.00', 1000, 27, 1, '2023-12-09 00:00:00'),
(69, 'N K Hall', 'Navsari Surat road, surat', '4000.00', 452, 27, 0, '2023-12-09 00:00:00'),
(70, 'La Victoria Banquet', 'Pal road Adajan, surat', '35000.00', 550, 28, 0, '2023-12-09 00:00:00'),
(71, 'Dream Festive', 'Gaurav Path , surat', '43000.00', 500, 28, 0, '2023-12-09 00:00:00'),
(72, 'Privates Voyage', 'Valentine Multiplex,surat', '100000.00', 4900, 29, 1, '2023-12-09 00:00:00'),
(73, 'Shreeji Vatika', 'piplod dumas road , surat', '75000.00', 3000, 29, 1, '2023-12-09 00:00:00'),
(74, 'Maharaja Farm', 'BRTS road , surat', '43000.00', 350, 31, 1, '2023-12-09 00:00:00'),
(84, 'Uday Palace', 'Surat', '12000.00', 80, 1, 1, '2024-02-01 00:00:00'),
(85, 'Aisha palace', 'Alipore, Chikhli', '35000.00', 4000, 1, 1, '2024-02-01 00:00:00'),
(86, 'Aisha palace', 'Alipore, Chikkhli', '35000.00', 500, 1, 1, '2024-02-01 00:00:00'),
(87, 'Aisha palace', 'Surat', '15000.00', 100, 1, 1, '2024-02-01 00:00:00'),
(89, 'Chotu Palace', 'Surat', '15000.00', 100, 1, 1, '2024-02-06 18:10:19'),
(90, 'SP Villa', 'Godadra, Surat', '50000.00', 300, 1, 1, '2024-03-18 21:14:17'),
(91, 'Kavita Palace', 'Vesu, Gujarat', '15000.00', 100, 4, 1, '2024-04-01 21:27:14');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_venue_book`
--

CREATE TABLE `tbl_venue_book` (
  `booking_id` int(11) NOT NULL,
  `venue_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_venue_book`
--

INSERT INTO `tbl_venue_book` (`booking_id`, `venue_id`) VALUES
(1, 1),
(3, 4),
(4, 1),
(5, 20),
(6, 1),
(7, 20),
(52, 1),
(54, 20),
(55, 4),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(67, 1),
(68, 1),
(69, 1),
(70, 1),
(71, 1),
(72, 1),
(73, 1),
(74, 1),
(75, 1),
(76, 1),
(77, 1),
(78, 1),
(79, 1),
(80, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_venue_image`
--

CREATE TABLE `tbl_venue_image` (
  `venue_img_id` int(11) NOT NULL,
  `venue_id` int(11) NOT NULL,
  `image_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_venue_image`
--

INSERT INTO `tbl_venue_image` (`venue_img_id`, `venue_id`, `image_name`) VALUES
(2, 22, 'img-1.jpg'),
(6, 36, 'venue.jpg'),
(9, 63, 'v10.jpg'),
(10, 1, 'radhekrishna.jpg'),
(11, 64, 'wed2.jpg'),
(12, 65, 'v7.jpg'),
(13, 66, 'hall4.jpg'),
(15, 4, 'saileela1.jpeg'),
(16, 4, 'saileela2.jpg'),
(17, 4, 'saileela3.jpg'),
(18, 4, 'saileela4.jpeg'),
(19, 4, 'saileela5.jpeg'),
(20, 4, 'saileela6.jpeg'),
(21, 20, 'EdenGardens.jpg'),
(27, 27, 'wedding1.png'),
(33, 33, 'WhatsApp Image 2023-11-28 at 10.10.39 AM.jpeg'),
(34, 90, 'Villa-Des-Vergers-Wedding-venue-italy-Italian-Wedding-Circle24-1.jpg'),
(35, 91, '52077-costofdestinationweddinginudaipur-hotelchundapalace-haveagrandweddingwithabreathtakingview-1.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_venue_services`
--

CREATE TABLE `tbl_venue_services` (
  `venue_service_id` int(11) NOT NULL,
  `venue_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `service_name` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `Qty` int(10) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_venue_services`
--

INSERT INTO `tbl_venue_services` (`venue_service_id`, `venue_id`, `service_id`, `service_name`, `price`, `Qty`, `status`) VALUES
(135, 1, 3, 'Servants', '1000.00', 100, 0),
(136, 1, 4, 'Chairs', '400.00', 200, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chatbot`
--
ALTER TABLE `chatbot`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `tbl_booking`
--
ALTER TABLE `tbl_booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `cid` (`user_id`);

--
-- Indexes for table `tbl_cart_service`
--
ALTER TABLE `tbl_cart_service`
  ADD PRIMARY KEY (`user_id`,`venue_service_id`);

--
-- Indexes for table `tbl_cart_venue`
--
ALTER TABLE `tbl_cart_venue`
  ADD PRIMARY KEY (`venue_id`,`user_id`);

--
-- Indexes for table `tbl_customer_payment`
--
ALTER TABLE `tbl_customer_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_meeting_requests`
--
ALTER TABLE `tbl_meeting_requests`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `tbl_messages`
--
ALTER TABLE `tbl_messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `tbl_scheduled_meetings`
--
ALTER TABLE `tbl_scheduled_meetings`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `tbl_service`
--
ALTER TABLE `tbl_service`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `tbl_service_book`
--
ALTER TABLE `tbl_service_book`
  ADD PRIMARY KEY (`booking_id`,`service_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tbl_vendor_payment`
--
ALTER TABLE `tbl_vendor_payment`
  ADD PRIMARY KEY (`vendor_pay_id`);

--
-- Indexes for table `tbl_venue`
--
ALTER TABLE `tbl_venue`
  ADD PRIMARY KEY (`venue_id`),
  ADD KEY `vid` (`vendor_id`);

--
-- Indexes for table `tbl_venue_book`
--
ALTER TABLE `tbl_venue_book`
  ADD PRIMARY KEY (`booking_id`,`venue_id`);

--
-- Indexes for table `tbl_venue_image`
--
ALTER TABLE `tbl_venue_image`
  ADD PRIMARY KEY (`venue_img_id`);

--
-- Indexes for table `tbl_venue_services`
--
ALTER TABLE `tbl_venue_services`
  ADD PRIMARY KEY (`venue_service_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `venue_id` (`venue_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chatbot`
--
ALTER TABLE `chatbot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_booking`
--
ALTER TABLE `tbl_booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `tbl_customer_payment`
--
ALTER TABLE `tbl_customer_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `tbl_meeting_requests`
--
ALTER TABLE `tbl_meeting_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_messages`
--
ALTER TABLE `tbl_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tbl_service`
--
ALTER TABLE `tbl_service`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `tbl_vendor_payment`
--
ALTER TABLE `tbl_vendor_payment`
  MODIFY `vendor_pay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_venue`
--
ALTER TABLE `tbl_venue`
  MODIFY `venue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `tbl_venue_image`
--
ALTER TABLE `tbl_venue_image`
  MODIFY `venue_img_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `tbl_venue_services`
--
ALTER TABLE `tbl_venue_services`
  MODIFY `venue_service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_booking`
--
ALTER TABLE `tbl_booking`
  ADD CONSTRAINT `tbl_booking_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_venue`
--
ALTER TABLE `tbl_venue`
  ADD CONSTRAINT `tbl_venue_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `tbl_user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_venue_services`
--
ALTER TABLE `tbl_venue_services`
  ADD CONSTRAINT `tbl_venue_services_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `tbl_service` (`service_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_venue_services_ibfk_2` FOREIGN KEY (`venue_id`) REFERENCES `tbl_venue` (`venue_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
