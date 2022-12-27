-- Add categories

INSERT INTO `user` (`email`, `name`, `pwd`, `contacts`) 
VALUES 
    ('mail90210@mail.ru', 'Dimitry', '827ccb0eea8a706c4c34a16891f84e7b', 'Санкт-Петербург'),
    ('undecided90210@yandex.ru', 'Undecided', '827ccb0eea8a706c4c34a16891f84e7b', 'Санкт-Петербург');

INSERT INTO `category` (`name`, `tag`) 
VALUES 
    ('Доски и лыжи', 'boards'),
    ('Крепления', 'attachment'),
    ('Ботинки', 'boots'),
    ('Одежда', 'clothing'),
    ('Инструменты', 'tools'),
    ('Разное', 'other');

-- Add some lots

INSERT INTO `lot` (`name`,`description`,`img`,`start_price`,`step`,`end_datetime`,`author_id`,`category_id`)
VALUES 
    ('2014 Rossignol District Snowboard','Крутанский стафф','img/lot-1.jpg', 10999, 200 ,'2023-10-19 04:24:17', 1 , 1),
    ('DC Ply Mens 2016/2017 Snowboard','Крутанский стафф','img/lot-2.jpg', 15999, 100 ,'2023-10-19 04:24:17', 2 , 1),
    ('Крепления Union Contact Pro 2015 года размер L/XL','Крутанский стафф','img/lot-3.jpg', 800, 50 ,'2023-10-19 04:24:17', 2 , 2),
    ('Ботинки для сноуборда DC Mutiny Charocal','Крутанский стафф','img/lot-4.jpg', 10999, 1000 ,'2023-10-19 04:24:17', 2 , 3),
    ('Куртка для сноуборда DC Mutiny Charocal','Крутанский стафф','img/lot-5.jpg', 7500, 100 ,'2023-10-19 04:24:17', 1 , 4),
    ('Маска Oakley Canopy','Крутанский стафф','img/lot-6.jpg', 5400, 100 ,'2023-10-19 04:24:17', 1 , 6);

-- Add some bets

INSERT INTO `bet` (`price`, `author_id`, `lot_id`)
VALUES
    (13000, 2, 1),
    (14000, 2, 1),
    (15000, 2, 1);

-- Categories list
SELECT * FROM `category`;

-- Lots list
SELECT `lot`.`name`, `lot`.`start_price`, `lot`.`img`, `lot`.`end_datetime`, `category`.`name` AS `category`, IF(MAX(`bet`.`price`), MAX(`bet`.`price`), `lot`.`start_price`) AS `price` FROM `lot` 
JOIN `category` ON `lot`.`category_id` = `category`.`id` 
LEFT JOIN `bet` ON `bet`.`lot_id` = `lot`.`id` 
WHERE `lot`.`end_datetime` > NOW() GROUP BY `lot`.`id` 
ORDER BY `lot`.`create_datetime` DESC;


-- One lot
SELECT `lot`.*, `category`.`name` AS `category_name` FROM `lot` JOIN `category` ON `lot`.`category_id` = `category`.`id` WHERE `lot`.`id` = 1;

-- Update lot
UPDATE `lot` SET `name` = 'Обновленный лот' WHERE `id` = 1;

-- Lots list
SELECT * FROM `lot`;

-- Lots bet
SELECT * FROM `bet` WHERE `lot_id` = 1 ORDER BY `create_datetime` DESC;

-- All lots from category
SELECT * FROM `lot` WHERE `category_id` = 1;

-- Bets list of lot
SELECT `user`.`name`, `bet`.`price`, `bet`.`create_datetime` FROM `bet` JOIN `user` ON `bet`.`author_id` = `user`.`id` WHERE `lot_id` = 1;

-- Lot + bets + category
SELECT lot.*, `user`.`name`, `bet`.`price`, `bet`.`create_datetime`, category.name, category.tag FROM `bet` JOIN `user` ON `bet`.`author_id` = `user`.`id` JOIN `lot` ON lot.author_id = `user`.`id` JOIN category ON category.id = lot.category_id WHERE `lot_id` = 1;