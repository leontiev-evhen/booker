-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Ноя 09 2017 г., 08:02
-- Версия сервера: 10.1.25-MariaDB-
-- Версия PHP: 7.0.22-0ubuntu0.17.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `booker`
--

-- --------------------------------------------------------

--
-- Структура таблицы `bk_events`
--

CREATE TABLE `bk_events` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_room` int(11) NOT NULL,
  `description` text NOT NULL,
  `time_start` int(11) NOT NULL,
  `time_end` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `bk_events`
--

INSERT INTO `bk_events` (`id`, `id_user`, `id_room`, `description`, `time_start`, `time_end`, `parent_id`, `create_at`) VALUES
(244, 8, 1, 'Morning meeting', 1510124400, 1510128000, 0, '2017-11-08 08:39:03'),
(245, 8, 1, 'Morning meeting', 1510729200, 1510732800, 244, '2017-11-08 08:39:03'),
(246, 8, 1, 'Morning meeting', 1511334000, 1511337600, 244, '2017-11-08 08:39:03'),
(247, 8, 1, 'Morning meeting', 1511938800, 1511942400, 244, '2017-11-08 08:39:03'),
(248, 8, 1, 'Morning meeting', 1512543600, 1512547200, 244, '2017-11-08 08:39:03'),
(249, 8, 2, 'Morning meeting', 1510124400, 1510126200, 0, '2017-11-08 08:39:29'),
(250, 8, 2, 'Morning meeting', 1511334000, 1511335800, 249, '2017-11-08 08:39:29'),
(251, 8, 2, 'Morning meeting', 1512543600, 1512545400, 249, '2017-11-08 08:39:29'),
(252, 7, 1, 'Meeting', 1510128000, 1510131600, 0, '2017-11-08 08:40:05'),
(253, 7, 1, 'Meeting', 1512720000, 1512723600, 252, '2017-11-08 08:40:05');

-- --------------------------------------------------------

--
-- Структура таблицы `bk_roles`
--

CREATE TABLE `bk_roles` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `bk_roles`
--

INSERT INTO `bk_roles` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'user');

-- --------------------------------------------------------

--
-- Структура таблицы `bk_rooms`
--

CREATE TABLE `bk_rooms` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `bk_rooms`
--

INSERT INTO `bk_rooms` (`id`, `name`) VALUES
(1, 'Room 1'),
(2, 'Room 2'),
(3, 'Room 3');

-- --------------------------------------------------------

--
-- Структура таблицы `bk_users`
--

CREATE TABLE `bk_users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_role` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `token_create_at` int(11) NOT NULL,
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `bk_users`
--

INSERT INTO `bk_users` (`id`, `name`, `email`, `password`, `id_role`, `token`, `token_create_at`, `create_at`) VALUES
(7, 'max', 'max@mail.com', '$2y$10$dLFOVxi00VKkrGT/p2N9PeQuuzoCiJhEAUXzgq3bMWgyLthKQSzQ2', 2, 'LRCKDvnCJqGzUb31GvbWDeyAdozEVZ2h', 1510124176, '2017-11-05 18:10:56'),
(8, 'yevhen', 'leo@mail.cz', '$2y$10$ApppotcNRkQjiIDRc/JTruCincj.T/.yEcl934GwcH4566JOrLHlS', 1, 'XqR5lWSIQuoGmuoMopLJ0N2MHzZs8X3V', 1510127972, '2017-11-05 18:19:07'),
(9, 'test', 'test@test.com', '$2y$10$KY7dJuS9vbbBsROU8oWOvegjzvphl8Kp3aKsAqMBz0LyPnrkc0v9e', 2, 'BRMpu3btOHkbtyXVavUtE4wkiwEWc4yd', 1509902599, '2017-11-05 18:19:17');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `bk_events`
--
ALTER TABLE `bk_events`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bk_roles`
--
ALTER TABLE `bk_roles`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bk_rooms`
--
ALTER TABLE `bk_rooms`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bk_users`
--
ALTER TABLE `bk_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `bk_events`
--
ALTER TABLE `bk_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=254;
--
-- AUTO_INCREMENT для таблицы `bk_roles`
--
ALTER TABLE `bk_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `bk_rooms`
--
ALTER TABLE `bk_rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `bk_users`
--
ALTER TABLE `bk_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
