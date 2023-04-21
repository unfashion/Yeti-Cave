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
    $sql = 'SELECT `lot`.*, `category`.`name` AS `category_name`, IF(MAX(`bet`.`price`), MAX(`bet`.`price`), `lot`.`start_price`) AS `price`
    FROM `lot` 
    JOIN `category` ON `lot`.`category_id` = `category`.`id` 
    LEFT JOIN `bet` ON `bet`.`lot_id` = `lot`.`id`
    WHERE `lot`.`id` =  ? GROUP BY `lot`.`id`';

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

function get_bets_by_lot($link, $id)
{
    $sql = "SELECT bet.*, user.name AS `author_name` FROM `bet` 
    JOIN `user` ON `user`.`id` = `bet`.`author_id`
    WHERE `lot_id` = ? ORDER BY `create_datetime` DESC";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : null;
    return [
        'data' => $data,
        'error' => mysqli_error($link)
    ];
}


function add_lot($link, $data)
{
    $sql = " INSERT INTO `lot` (`name`,`category_id`,`description`,`start_price`,`step`,`end_datetime`,`img`,`author_id`)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
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
function get_login_user($link, $data)
{
    $sql = "SELECT * FROM `user` WHERE `email` = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $data['email']);
    if (!mysqli_stmt_execute($stmt)) {
        $error = 'Ошибка запроса к БД';
    } else {
        $result = mysqli_stmt_get_result($stmt);
        $result = mysqli_fetch_assoc($result);
        $is_pass = $result ? password_verify($data['password'], $result['pwd']) : null;
        $error =  $is_pass ? null : 'Вы ввели неверный email/пароль';
    }
    $user = !$error ? $result : null;
    return [
        'user' => $user,
        'error' => $error,
    ];
}

function get_search_result($link, $search, $page, $limit)
{
    $offset = $page ? ($page * $limit - $limit) : 0;
    $data = [$search, $limit, $offset];
    $sql = "SELECT `lot`.`id`, `lot`.`name`, `lot`.`start_price`, `lot`.`img`, `lot`.`end_datetime`, `category`.`name` AS `category`, IF(MAX(`bet`.`price`), MAX(`bet`.`price`), `lot`.`start_price`) AS `price` 
    FROM `lot`
    JOIN `category` ON `lot`.`category_id` = `category`.`id` 
    LEFT JOIN `bet` ON `bet`.`lot_id` = `lot`.`id` 
    WHERE `lot`.`end_datetime` > NOW() 
    AND MATCH (`lot`.`name`, `lot`.`description`) AGAINST(?) GROUP BY `lot`.`id` 
    ORDER BY `lot`.`create_datetime` DESC LIMIT ? OFFSET ?";
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    if (!$stmt) {
        $error = 'Ошибка запроса к БД';
    } else {
        $result = mysqli_stmt_get_result($stmt);
        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    $error = $error ?? null;
    $lots = !$error ? $result : null;
    return [
        'data' => $lots,
        'error' => $error,
    ];
}

// function get_page_count($link, $search, $limit)
// {
//     $sql = "SELECT COUNT(*) as `count` FROM `lot` WHERE `lot`.`end_datetime` > NOW() 
//     AND MATCH (`lot`.`name`, `lot`.`description`) AGAINST(?)";
//     $stmt = mysqli_prepare($link, $sql);
//     mysqli_stmt_bind_param($stmt, 's', $search);
//     mysqli_stmt_execute($stmt);
//     $result = mysqli_stmt_get_result($stmt);
//     $data = $result ? mysqli_fetch_assoc($result) : '';
//     $data = ceil($data['count'] / $limit);
//     return [
//         'data' => $data,
//         'error' => mysqli_error($link)
//     ];
// }

function get_lots_from_cat($link, $category, $page, $limit)
{
    $offset = $page ? ($page * $limit - $limit) : 0;
    $data = [$category, $limit, $offset];
    $sql = "SELECT `lot`.`id`, `lot`.`name`, `lot`.`start_price`, `lot`.`img`, `lot`.`end_datetime`, `category`.`name` AS `category`, IF(MAX(`bet`.`price`), MAX(`bet`.`price`), `lot`.`start_price`) AS `price` 
    FROM `lot`
    JOIN `category` ON `lot`.`category_id` = `category`.`id` 
    LEFT JOIN `bet` ON `bet`.`lot_id` = `lot`.`id` 
    WHERE `lot`.`end_datetime` > NOW() 
    AND `lot`.`category_id` = ? GROUP BY `lot`.`id` 
    ORDER BY `lot`.`create_datetime` DESC LIMIT ? OFFSET ?";
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    if (!$stmt) {
        $error = 'Ошибка запроса к БД';
    } else {
        $result = mysqli_stmt_get_result($stmt);
        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    $error = $error ?? null;
    $lots = !$error ? $result : null;
    return [
        'data' => $lots,
        'error' => $error,
    ];
}



function get_page_count_search($link, $limit, $search)
{

    $sql = "SELECT COUNT(*) as `count` FROM `lot` WHERE `lot`.`end_datetime` > NOW() 
    AND MATCH (`lot`.`name`, `lot`.`description`) AGAINST(?)";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 's', $search);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = $result ? mysqli_fetch_assoc($result) : '';
    $data = ceil($data['count'] / $limit);
    return [
        'data' => $data,
        'error' => mysqli_error($link),
        '404' => false
    ];
}
function get_page_count_category($link, $limit, $id)
{

    $sql = "SELECT COUNT(*) as `count` FROM `lot` WHERE `end_datetime` > NOW() 
    AND category_id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = $result ? mysqli_fetch_assoc($result) : '';
    $data = ceil($data['count'] / $limit);
    return [
        'data' => $data,
        'error' => mysqli_error($link),
        '404' => false
    ];
}

function get_category_by_id($link, $id)
{
    $sql = "SELECT * FROM `category` WHERE id = $id";
    $result = mysqli_query($link, $sql);
    $data = $result ? mysqli_fetch_assoc($result) : '';
    return [
        'data' => $data,
        'error' => mysqli_error($link)
    ];
}


function add_bet($link, $bet, $lot_id, $author_id)
{
    $data['bet'] = $bet;
    $data['lot_id'] = $lot_id;
    $data['author_id'] = $author_id;

    $sql = "INSERT INTO `bet` (`price`,`lot_id`, `author_id`) VALUES (?, ?, ?)";
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    return [
        'id' => mysqli_stmt_execute($stmt) ? mysqli_insert_id($link) : null,
        'error' => mysqli_error($link)
    ];
}

function get_bets_by_user($link, $id)
{
    $sql = "SELECT l.id AS lot_id, 
    l.img AS lot_img, 
    l.name AS lot_name, 
    l.category_name AS category_name, 
    l.end_datetime AS lot_end_datetime, 
    l.winner_id,
    l.price AS lot_price,
    b.price AS bet_price, 
    b.create_datetime AS bet_create_datetime  
    FROM 
    (SELECT lot.*, category.name AS category_name, 
    IF(MAX(`bet`.`price`), MAX(`bet`.`price`), `lot`.`start_price`) AS `price` 
    FROM `lot`
    JOIN `category` ON `lot`.`category_id` = `category`.`id` 
    LEFT JOIN `bet` ON `bet`.`lot_id` = `lot`.`id` 
    GROUP BY `lot`.`id`) as l
    JOIN (SELECT * FROM `bet`) as b
    ON l.id = b.lot_id
    WHERE b.author_id = $id
    ORDER BY bet_create_datetime DESC";
    $result = mysqli_query($link, $sql);
    $data = $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : '';
    return [
        'data' => $data,
        'error' => mysqli_error($link)
    ];
}

function define_winner($link)
{
    // Получаем id всех лотов, которые вышли из срока и не имеют победителя
    $sql = "SELECT id FROM lot WHERE end_datetime < NOW() AND winner_id IS NULL";
    $result = mysqli_query($link, $sql);
    $undef_win_lots = $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : null;

    // Если массив получен и в нем есть непустые значения, 
    // то запускаем цикл, где на каждой итерации получаем id автора 
    // самой высокой ставки.
    if ($undef_win_lots and in_array(!null, $undef_win_lots)) {
        foreach ($undef_win_lots as $lot) {
            $lot_id = $lot['id'];
            $sql = "SELECT author_id FROM bet WHERE lot_id = $lot_id 
                    ORDER BY price DESC LIMIT 1";
            $bet = mysqli_query($link, $sql);
            $bet = mysqli_fetch_assoc($bet);
            $author_id = $bet['author_id'] ?? null;

            // Если победитель нашелся, записываем его id в графу победителя лота
            if ($author_id) {
                $sql = "UPDATE lot SET winner_id = $author_id WHERE id = $lot_id";
                mysqli_query($link, $sql);

                // и вызываем функцию отправки уведомления победителя
                send_message($link, $lot_id);
            }
        }
    }
}




// SELECT `lot`.`id`, `lot`.`name`, `lot`.`start_price`, lot.winner_id, `lot`.`img`, `lot`.`end_datetime`, `category`.`name` AS `category`, IF(MAX(`bet`.`price`), MAX(`bet`.`price`), `lot`.`start_price`) AS `price`
//     FROM `lot`
//     JOIN `category` ON `lot`.`category_id` = `category`.`id` 
//     LEFT JOIN `bet` ON `bet`.`lot_id` = `lot`.`id` AND lot.id = bet.lot_id
//     WHERE `lot`.`end_datetime` < NOW() AND lot.start_price != price AND lot.winner_id IS NULL
//     GROUP BY `lot`.`id` 
//     ORDER BY `lot`.`create_datetime` DESC 


//     CREATE VIEW a AS SELECT `lot`.`id`, `lot`.`name`, `lot`.`start_price`, lot.winner_id, `lot`.`img`, `lot`.`end_datetime`, `category`.`name` AS `category`, IF(MAX(`bet`.`price`), MAX(`bet`.`price`), `lot`.`start_price`) AS `price`
//     FROM `lot`
//     JOIN `category` ON `lot`.`category_id` = `category`.`id` 
//     LEFT JOIN `bet` ON `bet`.`lot_id` = `lot`.`id` AND lot.id = bet.lot_id
//     WHERE `lot`.`end_datetime` < NOW() AND lot.start_price != price AND lot.winner_id IS NULL
//     GROUP BY `lot`.`id` 
//     ORDER BY `lot`.`create_datetime` DESC;
    
// SELECT a.*, bet.author_id FROM a JOIN bet WHERE bet.price = a.price


// SELECT `lot`.`id`, `lot`.`name`, `lot`.`start_price`, `lot`.`img`, `lot`.`end_datetime`, `category`.`name` AS `category`, bet.price
//     FROM `lot`
//     JOIN `category` ON `lot`.`category_id` = `category`.`id` 
//  JOIN `bet` ON `bet`.`lot_id` = `lot`.`id` 
//     WHERE bet.author_id = 11 
//     ORDER BY `lot`.`create_datetime`




// SELECT `lot`.`id`, `lot`.`name`, `lot`.`start_price`, `lot`.`img`, `lot`.`end_datetime`, `category`.`name` AS `category`, IF(MAX(`bet`.`price`), MAX(`bet`.`price`), `lot`.`start_price`) AS `price` 
//     FROM `lot`
//     JOIN `category` ON `lot`.`category_id` = `category`.`id` 
//     LEFT JOIN `bet` ON `bet`.`lot_id` = `lot`.`id` 
// WHERE bet.author_id = 11 
//  GROUP BY `lot`.`id`;
 
//  SELECT * FROM `bet` as da WHERE da.author_id = 11;
 
//  SELECT * FROM 
 
//  (SELECT `lot`.`id`, `lot`.`name`, `lot`.`start_price`, `lot`.`img`, `lot`.`end_datetime`, `category`.`name` AS `category`, IF(MAX(`bet`.`price`), MAX(`bet`.`price`), `lot`.`start_price`) AS `price` 
//     FROM `lot`
//     JOIN `category` ON `lot`.`category_id` = `category`.`id` 
//     LEFT JOIN `bet` ON `bet`.`lot_id` = `lot`.`id` 
// WHERE bet.author_id = 11 
//  GROUP BY `lot`.`id`) as l
 
//  JOIN (SELECT * FROM `bet` as da WHERE da.author_id = 11) as b
 
//  WHERE l.id = b.lot_id




// SELECT * FROM 
 
// (SELECT `lot`.`id`, `lot`.`name`, `lot`.`start_price`, `lot`.`img`, `lot`.`end_datetime`, `category`.`name` AS `category`, IF(MAX(`bet`.`price`), MAX(`bet`.`price`), `lot`.`start_price`) AS `price` 
//     FROM `lot`
//     JOIN `category` ON `lot`.`category_id` = `category`.`id` 
//     LEFT JOIN `bet` ON `bet`.`lot_id` = `lot`.`id` 
// 	GROUP BY `lot`.`id`) as l
// JOIN (SELECT * FROM `bet`) as b
// WHERE l.id = b.lot_id




// SELECT * FROM 
 
// (SELECT lot.id, lot.winner_id, lot.img, lot.name AS lot_name, `lot`.`start_price`,  lot.img AS lot_img, lot.end_datetime AS lot_end_datetime, category.name AS category_name, IF(MAX(`bet`.`price`), MAX(`bet`.`price`), `lot`.`start_price`) AS `price` 
//     FROM `lot`
//     JOIN `category` ON `lot`.`category_id` = `category`.`id` 
//     LEFT JOIN `bet` ON `bet`.`lot_id` = `lot`.`id` 
// 	GROUP BY `lot`.`id`) as l
// JOIN (SELECT * FROM `bet`) as b
// WHERE l.id = b.lot_id


// SELECT * FROM 
 
// (SELECT lot.*, category.name AS category_name, IF(MAX(`bet`.`price`), MAX(`bet`.`price`), `lot`.`start_price`) AS `price` 
//     FROM `lot`
//     JOIN `category` ON `lot`.`category_id` = `category`.`id` 
//     LEFT JOIN `bet` ON `bet`.`lot_id` = `lot`.`id` 
// 	GROUP BY `lot`.`id`) as l
// JOIN (SELECT * FROM `bet`) as b
// WHERE l.id = b.lot_id