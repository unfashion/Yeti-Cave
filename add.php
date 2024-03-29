<?php
session_start();
if(!isset($_SESSION['id'])){
header('Location: /sign-up.php');
exit();
}

require_once('helpers.php');
require_once('functions.php');
require_once('init.php');
require_once('models.php');

$author_id = $_SESSION['id'];
$categories = get_categories_list($link);
$cats_ids = array_column($categories['data'], 'id');
$required_fields = ['lot_name', 'category_id', 'message', 'lot_rate', 'lot_step', 'lot_date'];
$errors = array_fill_keys($required_fields, null);
$errors['lot_img'] = $errors['lot_img'] ?? null;
$errors['add_lot'] = $errors['add_lot'] ?? null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $rules = [
        'lot_name' => function ($value) {
            return validate_length($value, 8, 80);
        },
        'category_id' => function ($value) use ($cats_ids) {
            return validate_category($value, $cats_ids);
        },
        'message' => function ($value) {
            return validate_message($value);
        },
        'lot_rate' => function ($value) {
            return validate_rate($value);
        },
        'lot_step' => function ($value) {
            return validate_step($value);
        },
        'lot_date' => function ($value) {
            return validate_date($value);
        }
    ];
    $lot = filter_input_array(INPUT_POST, [
        'lot_name' => 'FILTER_DEFAULT', 
        'category_id' => 'FILTER_DEFAULT', 
        'message' => 'FILTER_DEFAULT', 
        'lot_rate' => 'FILTER_DEFAULT', 
        'lot_step' => 'FILTER_DEFAULT', 
        'lot_date' => 'FILTER_DEFAULT'
    ], true);
    foreach ($lot as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }
    }
    
    $errors['lot_img'] = validate_img();
    if (!in_array(!null, $errors)) {
        $extension = pathinfo($_FILES['lot_img']['name'], PATHINFO_EXTENSION);
        $lot['lot_img'] = 'uploads/' . uniqid() . ".$extension";
        $lot['author_id'] = $author_id;
        move_uploaded_file($_FILES['lot_img']['tmp_name'], $lot['lot_img']);
        $add_lot = add_lot($link, $lot);

        if ($add_lot['id']) {
            $lot_id = $add_lot['id'];
            header("Location: /lot.php?id=$lot_id");
        } else {
            $errors['add_lot'] = $add_lot['error'];
        }
    }
}

$main_content = include_template('add.php', ['categories' => $categories['data'], 'errors' => $errors]);
$layout_content = include_template('layout.php', ['categories' => $categories['data'], 'content' => $main_content, 'title' => '']);
print($layout_content);
