-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Апр 18 2013 г., 20:57
-- Версия сервера: 5.5.16
-- Версия PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `qr-code-reader`
--

-- --------------------------------------------------------

--
-- Структура таблицы `deleted`
--

CREATE TABLE IF NOT EXISTS `deleted` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `record_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `history`
--

CREATE TABLE IF NOT EXISTS `history` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `code` varchar(1024) COLLATE utf8_bin NOT NULL,
  `date` int(11) NOT NULL,
  `record_info` varchar(256) COLLATE utf8_bin NOT NULL,
  `type_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `history`
--

INSERT INTO `history` (`record_id`, `latitude`, `longitude`, `code`, `date`, `record_info`, `type_id`, `user_id`) VALUES
(1, 34555543, 453453, 'ghter67y5hnhgn6y7bn67', 3453543, '', 1, 1),
(2, 3232, 3243, '234554gfvgbgbhfbhgd', 2343254, '', 2, 1),
(3, 3244432432, 34534543, 'sdfdbdfg43try', 234356576, '', 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `type`
--

CREATE TABLE IF NOT EXISTS `type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_description` char(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `type`
--

INSERT INTO `type` (`type_id`, `type_description`) VALUES
(1, 'qr'),
(2, 'bar');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(16) NOT NULL AUTO_INCREMENT,
  `gender` tinyint(1) NOT NULL,
  `birthday` int(11) NOT NULL,
  `locale` varchar(255) COLLATE utf8_bin NOT NULL,
  `user_email` char(255) COLLATE utf8_bin NOT NULL,
  `user_password` char(255) COLLATE utf8_bin NOT NULL,
  `user_first_name` char(255) COLLATE utf8_bin NOT NULL,
  `user_last_name` char(255) COLLATE utf8_bin NOT NULL,
  `is_facebook_user` tinyint(1) NOT NULL,
  `facebook_user_id` varchar(16) COLLATE utf8_bin NOT NULL,
  `facebook_username` varchar(256) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `email` (`user_email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `gender`, `birthday`, `locale`, `user_email`, `user_password`, `user_first_name`, `user_last_name`, `is_facebook_user`, `facebook_user_id`, `facebook_username`) VALUES
(1, 0, 0, '', 'ebalda@gmail.com', '1234567', 'Test', 'Test', 0, '0', ''),
(2, 0, 0, '', 'james@co.uk', '123456', 'bobby', 'statem', 1, '12345678', 'james');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
