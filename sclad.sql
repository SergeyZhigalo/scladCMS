-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Дек 29 2020 г., 23:26
-- Версия сервера: 10.3.13-MariaDB-log
-- Версия PHP: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `sclad`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `category`) VALUES
(1, 'общестроительные материалы'),
(2, 'сухие строительные смеси и гидроизоляция\r\n'),
(3, 'теплоизоляция и шумоизоляция\r\n'),
(4, 'материалы для сухого строительства\r\n'),
(5, 'древесно-плитные материалы\r\n'),
(6, 'пиломатериалы'),
(7, 'электроинструмент и комплектующие'),
(8, 'кровля, сайдинг, водосточные системы'),
(9, 'лакокрасочные материалы'),
(10, 'пены, клеи, герметики'),
(11, 'керамическая плитка и затирки'),
(12, 'финишная отделка стен и потолков'),
(13, 'напольные покрытия'),
(14, 'двери, окна, скобяные изделия'),
(15, 'инженерная сантехника'),
(16, 'отопительное и насосное оборудование'),
(17, 'сантехника'),
(18, 'электрика'),
(19, 'кабель и аксессуары'),
(20, 'климатическое оборудование'),
(21, 'ручной инструмент, спецодежда, хозтовары'),
(22, 'крепеж'),
(23, 'садовые и сезонные товары');

-- --------------------------------------------------------

--
-- Структура таблицы `manufacturers`
--

CREATE TABLE `manufacturers` (
  `id` varchar(255) NOT NULL,
  `manufacturer` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contactPerson` varchar(255) NOT NULL,
  `phone` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `manufacturers`
--

INSERT INTO `manufacturers` (`id`, `manufacturer`, `address`, `contactPerson`, `phone`) VALUES
('1876ak12', 'TEGOLA1', 'Малая Грузинская ул., 28, Москва, 1235571', 'Борисова Адеми Валентиновна1', '89198731588'),
('3251ne26', 'ООО Упячка', '1-й Щипковский пер., 23, Москва, 115093', 'Зубенко Михаил Петрович', '89163452965'),
('9573fp12', 'ATLAS', 'ул. Научный Городок (Луговая), 21, Лобня, Московская обл., 141055', 'Крылов Ахмадинежат Олегович', '89578896945');

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `barcode` int(11) NOT NULL,
  `categoryNumber` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `manufacturerNumber` varchar(255) NOT NULL,
  `purchasePrice` int(11) NOT NULL,
  `sellingPrice` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`barcode`, `categoryNumber`, `title`, `manufacturerNumber`, `purchasePrice`, `sellingPrice`) VALUES
(106459, 4, 'Поручень 44х67х3000 мм', '3251ne26', 600, 1000),
(154638, 4, 'ЦСП 16х1250х3200 мм', '9573fp12', 1500, 1800),
(659173, 7, 'Мультиметр Mastech', '1876ak12', 7950, 8200);

-- --------------------------------------------------------

--
-- Структура таблицы `reasonoperation`
--

CREATE TABLE `reasonoperation` (
  `id` int(11) NOT NULL,
  `cause` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `reasonoperation`
--

INSERT INTO `reasonoperation` (`id`, `cause`) VALUES
(1, 'прием'),
(2, 'продажа'),
(3, 'заводской брак'),
(4, 'порча товара');

-- --------------------------------------------------------

--
-- Структура таблицы `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `role` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `role`
--

INSERT INTO `role` (`id`, `role`) VALUES
(1, 'администратор'),
(2, 'менеджер'),
(3, 'работник');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role` int(11) NOT NULL,
  `login` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `role`, `login`, `password`) VALUES
(3, 3, 'rab', '123'),
(10, 1, 'admin', '123'),
(16, 2, 'moder', '123');

-- --------------------------------------------------------

--
-- Структура таблицы `warehouseoperations`
--

CREATE TABLE `warehouseoperations` (
  `operationNumber` int(11) NOT NULL,
  `reasonOperationNumber` int(11) NOT NULL,
  `dateOperation` date NOT NULL,
  `barcode` int(11) NOT NULL,
  `numderBatch` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `warehouseoperations`
--

INSERT INTO `warehouseoperations` (`operationNumber`, `reasonOperationNumber`, `dateOperation`, `barcode`, `numderBatch`, `quantity`) VALUES
(17, 2, '2020-06-11', 154638, 12, 10);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `manufacturers`
--
ALTER TABLE `manufacturers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`barcode`),
  ADD KEY `categoryNumber` (`categoryNumber`),
  ADD KEY `manufacturerNumber` (`manufacturerNumber`);

--
-- Индексы таблицы `reasonoperation`
--
ALTER TABLE `reasonoperation`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `role` (`role`);

--
-- Индексы таблицы `warehouseoperations`
--
ALTER TABLE `warehouseoperations`
  ADD PRIMARY KEY (`operationNumber`),
  ADD KEY `barcode` (`barcode`),
  ADD KEY `reasonOperationNumber` (`reasonOperationNumber`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT для таблицы `reasonoperation`
--
ALTER TABLE `reasonoperation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `warehouseoperations`
--
ALTER TABLE `warehouseoperations`
  MODIFY `operationNumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_3` FOREIGN KEY (`categoryNumber`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `products_ibfk_4` FOREIGN KEY (`manufacturerNumber`) REFERENCES `manufacturers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `warehouseoperations`
--
ALTER TABLE `warehouseoperations`
  ADD CONSTRAINT `warehouseoperations_ibfk_2` FOREIGN KEY (`reasonOperationNumber`) REFERENCES `reasonoperation` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `warehouseoperations_ibfk_3` FOREIGN KEY (`barcode`) REFERENCES `products` (`barcode`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
