<?php
session_start();
$db = require_once('config/db.php');
$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database'] );
mysqli_set_charset($link, 'utf8');