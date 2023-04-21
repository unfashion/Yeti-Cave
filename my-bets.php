<?php
require_once('helpers.php');
require_once('functions.php');
require_once('data.php');
require_once('init.php');
require_once('mail.php');
require_once('models.php');

$errors = [];
$limit = 1;
$categories = get_categories_list($link);

if (isset($_SESSION['id'])){
    $user_id = $_SESSION['id'];
    define_winner($link);
    $bets = get_bets_by_user($link, $user_id);
} else {
    header("location: /404.php");
}
$main_content = include_template('my-bets.php', [
    'categories' => $categories['data'], 
    'bets' => $bets['data'],
    'user_id' => $user_id
]);
if ($errors) {
} else {
    $layout_content = include_template('layout.php', [
        'categories' => $categories['data'], 
        'content' => $main_content, 
        'title' => 'Поиск', 
        'is_auth' => $is_auth
    ]);
    print($layout_content);
}
