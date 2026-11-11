-- База данных для лабораторной работы № 5
-- Импорт: phpMyAdmin -> Импорт -> выбрать этот файл

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `lab5_data`
    DEFAULT CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `lab5_data`;

DROP TABLE IF EXISTS `terms`;
CREATE TABLE `terms` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `term` varchar(100) NOT NULL,
    `definition` text NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `terms` (`term`, `definition`) VALUES
('HTML', 'Стандартный язык разметки документов во Всемирной паутине; с его помощью создаётся структура веб-страницы.'),
('CSS', 'Язык описания внешнего вида документа, отвечающий за оформление, цвета, шрифты и расположение элементов.'),
('JavaScript', 'Язык программирования, который выполняется в браузере и делает веб-страницы интерактивными.'),
('PHP', 'Серверный скриптовый язык программирования, применяемый для создания динамических веб-сайтов.'),
('MySQL', 'Реляционная система управления базами данных, хранящая данные в таблицах и работающая по языку SQL.'),
('HTTP', 'Протокол передачи гипертекста, по которому браузер запрашивает страницы у веб-сервера.'),
('DOM', 'Объектная модель документа, представляющая HTML-страницу в виде дерева объектов для доступа из скриптов.'),
('URL', 'Унифицированный указатель ресурса, задающий адрес документа или другого объекта в сети.'),
('API', 'Программный интерфейс приложения, набор правил и функций для взаимодействия программ между собой.'),
('Git', 'Распределённая система управления версиями, отслеживающая изменения файлов проекта.');

DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `img` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `images` (`name`, `img`) VALUES
('HTML', 'html.svg'),
('CSS', 'css.svg'),
('JavaScript', 'javascript.svg'),
('PHP', 'php.svg'),
('MySQL', 'mysql.svg'),
('HTTP', 'http.svg'),
('DOM', 'dom.svg'),
('URL', 'url.svg'),
('API', 'api.svg'),
('Git', 'git.svg');

SET FOREIGN_KEY_CHECKS = 1;
