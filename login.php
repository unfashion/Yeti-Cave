<?php
if (isset($_SESSION['id'])) {
    header('HTTP/1.1 403 Forbidden');
    exit();
}

require_once('helpers.php');
require_once('functions.php');
require_once('data.php');
require_once('init.php');
require_once('models.php');

$categories = get_categories_list($link);
$errors = array_fill_keys(['email', 'password', 'identify'], null);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $rules = [
        'email' => function ($value) use ($link) {
            return validate_login_email($value, $link);
        },
        'password' => function ($value) {
            return validate_password($value);
        },
    ];

    $fields = [
        'email' => 'FILTER_DEFAULT',
        'password' => 'FILTER_DEFAULT',
    ];

    $login_data = filter_input_array(INPUT_POST, $fields, true);

    foreach ($login_data as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }
    }

    if (!in_array(!null, $errors)) {
        $user_data = get_login_user($link, $login_data);
        $errors['identify'] = $user_data['error'];
        if (!$user_data['error']) {
            session_start();
            $_SESSION['id'] = $user_data['user']['id'];
            $_SESSION['name'] = $user_data['user']['name'];
            print_r($_SESSION['id']);
            header('Location: /');
        }
    }
}

$main_content = include_template('login.php', [
    'categories' => $categories['data'],
    'errors' => $errors,
]);

$layout_content = include_template('layout.php', [
    'categories' => $categories['data'],
    'content' => $main_content,
    'username' => $username,
    'title' => 'Зарегистрироваться',
    'is_auth' => $is_auth,
]);

print($layout_content);
