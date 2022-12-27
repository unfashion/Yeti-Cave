<?php
require_once('helpers.php');
require_once('functions.php');
require_once('data.php');
require_once('init.php');
require_once('models.php');

$errors = [];
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (empty($id)) {
    header("location: /404.php");
    $udod = 'pusto';
} else if (!$link) {
    $errors[] = mysqli_connect_error();
} else {
    $lot = get_lot($link, $id);
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
    print_r($lot);
    $main_content = include_template('lot.php', ['categories' => $categories['data'], 'lot' => $lot['data']]);
}
if ($errors) {
} else {
    $layout_content = include_template('layout.php', ['categories' => $categories['data'], 'content' => $main_content, 'username' => $username, 'title' => $lot['data']['name'], 'is_auth' => $is_auth]);
    print($layout_content);
}
