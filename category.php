<?php
require_once('helpers.php');
require_once('functions.php');
require_once('init.php');
require_once('models.php');

$errors = [];
$limit = 6;
$categories = get_categories_list($link);
$params = [
    'id' => FILTER_SANITIZE_NUMBER_INT,
    'page' => FILTER_SANITIZE_NUMBER_INT,
];

$params = filter_input_array(INPUT_GET, $params, true);

$category = get_category_by_id($link, $params['id']);
$category = $category['data'] ?? null;

$page_count = get_page_count_category($link, $limit, $params['id']);
$page = $params['page'] ?? 1;
$lots = $params ? get_lots_from_cat($link, $params['id'], $page, $limit) : null;


$main_content = include_template('category.php', [
    'categories' => $categories['data'], 
    'lots' => $lots['data'] ?? null, 
    'category' => $category ?? null,
    'page_count' => $page_count['data'],
    'page' => $page
]);
if ($errors) {
} else {
    $layout_content = include_template('layout.php', [
        'categories' => $categories['data'], 
        'content' => $main_content, 
        'title' => 'Поиск', 
    ]);
    print($layout_content);
}
