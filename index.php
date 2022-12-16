<?php
require_once('helpers.php');
require_once('functions.php');
require_once('data.php');

$main_content = include_template('main.php', ['categories' => $categories, 'ads' => $ads]);
$layout_content = include_template('layout.php', ['content' => $main_content, 'username' => $username, 'title' => 'Главная', 'is_auth' => $is_auth]);

print($layout_content);
