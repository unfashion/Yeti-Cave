<?php

/**
 * Get categories list
 * @return array
 */
function get_categories_list($link)
{
    $sql = 'SELECT * FROM `category`';
    $result = mysqli_query($link, $sql);
    $data = $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : '';
    return [
        'data' => $data,
        'error' => mysqli_error($link)
    ];
}
function get_lots_list($link)
{
    $sql = 'SELECT `lot`.`id`, `lot`.`name`, `lot`.`start_price`, `lot`.`img`, `lot`.`end_datetime`, `category`.`name` AS `category`, IF(MAX(`bet`.`price`), MAX(`bet`.`price`), `lot`.`start_price`) AS `price` FROM `lot` 
    JOIN `category` ON `lot`.`category_id` = `category`.`id` 
    LEFT JOIN `bet` ON `bet`.`lot_id` = `lot`.`id` 
    WHERE `lot`.`end_datetime` > NOW() GROUP BY `lot`.`id` 
    ORDER BY `lot`.`create_datetime` DESC';
    $result = mysqli_query($link, $sql);
    $data = $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : '';
    return [
        'data' => $data,
        'error' => mysqli_error($link)
    ];
}
function get_lot($link, $id)
{
    // $sql = 'SELECT `lot`.*, `category`.`name` AS `category_name` 
    // IF(MAX(`bet`.`price`), MAX(`bet`.`price`), `lot`.`start_price`) AS `price` 
    // FROM `lot` 
    // JOIN `category` ON `lot`.`category_id` = `category`.`id` 
    // LEFT JOIN `bet` ON `bet`.`lot_id` = `lot`.`id` 
    // WHERE `lot`.`id` =  ?';

    $sql = 'SELECT `lot`.*, `category`.`name` AS `category_name`
    FROM `lot` 
    JOIN `category` ON `lot`.`category_id` = `category`.`id` 
    WHERE `lot`.`id` =  ?';

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = $result ? mysqli_fetch_assoc($result) : '';
    return [
        'data' => $data,
        'error' => mysqli_error($link)
    ];
}

function add_lot($link, $data)
{
    $sql = " INSERT INTO `lot` (`name`,`category_id`,`description`,`start_price`,`step`,`end_datetime`,`author_id`,`img`)
    VALUES (?, ?, ?, ?, ?, ?, 1, ?)";
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    return [
        'id' => mysqli_stmt_execute($stmt) ? mysqli_insert_id($link) : null,
        'error' => mysqli_error($link)
    ];
}
function add_user($link, $data)
{
    $data['name'] = htmlspecialchars($data['name']);
    $data['message'] = htmlspecialchars($data['message']);
    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    $sql = "INSERT INTO `user` (`email`,`pwd`,`name`,`contacts`) VALUES (?, ?, ?, ?)";
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    return [
        'id' => mysqli_stmt_execute($stmt) ? mysqli_insert_id($link) : null,
        'error' => mysqli_error($link)
    ];
}