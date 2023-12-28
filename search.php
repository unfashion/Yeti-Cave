<?php
require_once('helpers.php');
require_once('functions.php');
require_once('init.php');
require_once('models.php');

$errors = [];
$limit = 4;
$categories = get_categories_list($link);
$params = [
    'search' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    'page' => FILTER_SANITIZE_NUMBER_INT,
];

$params = filter_input_array(INPUT_GET, $params, true);
$params = $params ?? null;
$page_count = get_page_count_search($link, $limit, $params['search']);
$lots = $params ?  get_search_result($link, $params['search'], $params['page'], $limit) : null;
$page = $params['page'] ?? 1;


$main_content = include_template('search.php', [
    'categories' => $categories['data'], 
    'lots' => $lots['data'] ?? null, 
    'query' => $params['search'] ?? null,
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
