<?php
require_once('helpers.php');
require_once('functions.php');
require_once('data.php');
require_once('init.php');
require_once('models.php');

$errors = [];
$bet_error = null;
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (empty('id')) {
    header("location: /404.php");
}



if (!$link) {
    $errors[] = mysqli_connect_error();
} else {


    $lot = get_lot($link, $id);
    $min_bet = $lot['data']['price'] + $lot['data']['step'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $bet = filter_input(INPUT_POST, 'cost', FILTER_DEFAULT);
        $bet_error = validate_bet($bet, $min_bet);
        if (!$bet_error) {
            $add_bet = add_bet($link, $bet, $id, $_SESSION['id']);
            $lot['data']['price'] = $bet;
            $min_bet = $bet + $lot['data']['step'];
        }
    }
    $bets = get_bets_by_lot($link, $id);

    //   print_r($lot);


    if (!$lot['data']) {
        header("location: /404.php");
    }
    $categories = get_categories_list($link);
    if ($categories['error']) {
        $errors[] = $categories['error'];
    }
    if ($lot['error']) {
        $errors[] = $categories['error'];
    }
    $main_content = include_template('lot.php', [
        'categories' => $categories['data'],
        'lot' => $lot['data'],
        'bets' => $bets['data'],
        'min_bet' => $min_bet,
        'bet_error' => $bet_error,
    ]);
}
if ($errors) {
} else {
    $layout_content = include_template('layout.php', [
        'categories' => $categories['data'],
        'content' => $main_content,
        //        'username' => $username, 
        'title' => $lot['data']['name'],
        'is_auth' => $is_auth
    ]);
    print($layout_content);
}
