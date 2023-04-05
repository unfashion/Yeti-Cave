DROP DATABASE IF EXISTS `yeticave`;
CREATE DATABASE `yeticave` DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;

USE `yeticave`;

CREATE TABLE `category` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(128) NOT NULL UNIQUE,
    `tag` VARCHAR(128) NOT NULL UNIQUE
);

CREATE TABLE `lot` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(128) NOT NULL,
    `description` TEXT,
    `img` VARCHAR(128) NOT NULL,
    `start_price` INT UNSIGNED NOT NULL,
    `step` INT UNSIGNED NOT NULL,
    `create_datetime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `end_datetime` DATETIME NOT NULL,
    `author_id` INT UNSIGNED NOT NULL,
    `category_id` INT UNSIGNED NOT NULL,
    `winner_id` INT UNSIGNED
);

CREATE TABLE `bet` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `create_datetime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `price` INT UNSIGNED NOT NULL,
    `author_id` INT UNSIGNED NOT NULL,
    `lot_id` INT UNSIGNED NOT NULL
);

CREATE TABLE `user` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `reg_datetime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `email` VARCHAR(128) NOT NULL UNIQUE,
    `name` VARCHAR(128) NOT NULL,
    `pwd` CHAR(60) NOT NULL,
    `contacts` TEXT NOT NULL
);

CREATE INDEX `lot_category` ON `lot`(`category_id`);
CREATE INDEX `bet_lot` ON `bet`(`lot_id`);
CREATE UNIQUE INDEX `user_id` ON `user`(`id`);
CREATE UNIQUE INDEX `lot_id` ON `lot`(`id`);

-- Fulltext index
CREATE FULLTEXT INDEX `lot_ft_search` ON `lot` (`name`,`description`);