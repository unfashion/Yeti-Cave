<?php
session_start();

require_once('helpers.php');
require_once('functions.php');
require_once('data.php');
require_once('init.php');
require_once('models.php');

$errors = [];

if (!$link) {
    $errors[] = mysqli_connect_error();
} else {
    $categories = get_categories_list($link);
    if ($categories['error']) {
        $errors[] = $categories['error'];
    }
    $lots = get_lots_list($link);
    if ($lots['error']) {
        $errors[] = $categories['error'];
    }
    $main_content = include_template('main.php', ['categories' => $categories['data'], 'lots' => $lots['data']]);
}
if ($errors) {
    print_r($errors);
} else {
    $layout_content = include_template('layout.php', ['categories' => $categories['data'], 'content' => $main_content, 'username' => $username, 'title' => 'Главная', 'is_auth' => $is_auth]);
    print($layout_content);
}


