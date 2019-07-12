-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: mysql-srv84365.ht-systems.ru
-- Время создания: Июл 01 2019 г., 11:49
-- Версия сервера: 5.6.42
-- Версия PHP: 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `srv84365_shop2`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `session` text,
  `user` text,
  `id_product` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `cart`
--

INSERT INTO `cart` (`id`, `session`, `user`, `id_product`, `quantity`, `status`) VALUES
(46, '9e1efvf8g9vo406g1p2rn2lfv31pa88m', NULL, 1, 1, 0),
(47, '9e1efvf8g9vo406g1p2rn2lfv31pa88m', NULL, 2, 1, 0),
(48, '9e1efvf8g9vo406g1p2rn2lfv31pa88m', NULL, 2, 1, 0),
(52, 'dcfe8e84066c371c03a7d1e3b4f07f9d', 'admin', 2, 1, 0),
(53, 'dcfe8e84066c371c03a7d1e3b4f07f9d', 'admin', 2, 1, 0),
(54, '7d4bfedcb61518175047918c6f4f5a24', NULL, 3, 1, 1),
(55, '7d4bfedcb61518175047918c6f4f5a24', NULL, 1, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `auth` tinyint(1) DEFAULT NULL,
  `user` text,
  `session` text,
  `name` text,
  `phone` text,
  `products` text,
  `status` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `auth`, `user`, `session`, `name`, `phone`, `products`, `status`) VALUES
(39, 1, 'Мазай', '8u7pu5tit1qljrnk49bsl9fo1q3ljb6q', 'Дедушка Мазай', '8 999 555 33 22', 'Array', 'new'),
(40, 1, 'Бонд', '8u7pu5tit1qljrnk49bsl9fo1q3ljb6q', 'Джеймс Бонд', '007-007-007', 'Array', 'new'),
(50, 0, NULL, 'dcfe8e84066c371c03a7d1e3b4f07f9d', 'Ляйсан', '', 'Array', 'new');

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `img` text,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `img`, `price`) VALUES
(1, '88 Rue Du Rhone 87WA12BROWN', 'Водонепроницаемость 5 атм, материал - сталь/PVD покрытие, диаметр - 42 мм, механизм - кварцевый хронограф', '1', 32600),
(2, '88 Rue Du Rhone 87WA1200BLUE', 'Водонепроницаемость 5 атм, материал - сталь, диаметр - 42 мм, механизм - кварцевый хронограф', '2', 34100),
(3, 'Aviator V.3.20.1.147.4WHITE', 'Водонепроницаемость 10 атм, материал - сталь/PVD покрытие, диаметр - 45 мм, механизм - механика с автоподзаводом ', '3', 80400);

-- --------------------------------------------------------

--
-- Структура таблицы `sold_products`
--

CREATE TABLE `sold_products` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` text NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `sold_products`
--

INSERT INTO `sold_products` (`order_id`, `product_id`, `product_name`, `price`, `quantity`) VALUES
(39, 1, '88 Rue Du Rhone 87WA12BROWN', 32600, 1),
(39, 3, 'Aviator V.3.20.1.147.4WHITE', 80400, 1),
(39, 2, '88 Rue Du Rhone 87WA1200BLUE', 34100, 1),
(40, 3, 'Aviator V.3.20.1.147.4WHITE', 80400, 1),
(40, 2, '88 Rue Du Rhone 87WA1200BLUE', 34100, 1),
(40, 2, '88 Rue Du Rhone 87WA1200BLUE', 34100, 1),
(50, 2, '88 Rue Du Rhone 87WA1200BLUE', 34100, 1),
(50, 2, '88 Rue Du Rhone 87WA1200BLUE', 34100, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` text NOT NULL,
  `pass` text NOT NULL,
  `hash` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `pass`, `hash`) VALUES
(2, 'Мазай', '$2y$10$24qLR1lElJcmoLcF7F2IGuXEM77om/e9iSNf6eHiBNrDFs/umSDOC', ''),
(3, 'Бонд', '$2y$10$24qLR1lElJcmoLcF7F2IGuXEM77om/e9iSNf6eHiBNrDFs/umSDOC', '12584053885ca4f615c73628.12165116'),
(4, 'admin', '$2y$10$24qLR1lElJcmoLcF7F2IGuXEM77om/e9iSNf6eHiBNrDFs/umSDOC', '19448545715ca85ed8c1c085.45332628');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `sold_products`
--
ALTER TABLE `sold_products`
  ADD KEY `for_orders` (`order_id`) USING BTREE;

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
