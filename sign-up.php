<?php
require_once('helpers.php');
require_once('functions.php');
require_once('data.php');
require_once('init.php');
require_once('models.php');

$categories = get_categories_list($link);
$required_fields = ['email', 'password', 'name', 'message'];
$errors = array_fill_keys($required_fields, null);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $rules = [
        'email' => function ($value) use ($link) {
            return validate_email($value, $link);
        },
        'password' => function ($value) {
            return validate_password($value);
        },
        'name' => function ($value) {
            return validate_name($value);
        },
        'message' => function ($value) {
            return validate_feedback($value);
        },
    ];

    $fields = [
        'email' => 'FILTER_DEFAULT',
        'password' => 'FILTER_DEFAULT',
        'name' => 'FILTER_DEFAULT',
        'message' => 'FILTER_DEFAULT'
    ];

    $sign_up = filter_input_array(INPUT_POST, $fields, true);

    foreach ($sign_up as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }
    }

    if (!in_array(!null, $errors)) {
        $data = $_POST;
        $add_user = add_user($link, $data);
        header("Location: /login.php");
    }
}

$main_content = include_template('sign-up.php', [
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
