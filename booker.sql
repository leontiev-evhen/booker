-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Ноя 02 2017 г., 15:27
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
(1, 1, 1, 'test', 1509613200, 1509615000, 0, '2017-11-02 00:00:00'),
(2, 1, 1, 'test', 1509616800, 1509620400, 0, '2017-11-02 00:00:00'),
(3, 1, 1, 'test2', 1509879600, 1509883200, 0, '2017-11-02 00:00:00'),
(4, 1, 1, 'test3', 1510056000, 1510057800, 0, '2017-11-02 00:00:00');

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
(2, 'Room 2');

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
(1, 'test', 'test@test.com', '$2y$10$SNI/U6z2Jd7Aa3.9pBdEiOxCQye5fJrYUE8..hx3nrLc5UOgZ8JnW', 2, 'SXq8igciGqPQKJRwcteEhhb3RYYnJHfc', 1509631495, '2017-11-02 08:19:31');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `bk_roles`
--
ALTER TABLE `bk_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `bk_rooms`
--
ALTER TABLE `bk_rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `bk_users`
--
ALTER TABLE `bk_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
