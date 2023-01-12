<?php

/**
 * Price formatting
 * @param int $price - start price
 * @return string - formatted price
 */
function price_format($price)
{
    $formatted_price = number_format($price, 0, '.', ' ');
    return $formatted_price . '<b class="rub">р</b>';
}
function time_left($date)
{
    $date_timestamp = strtotime($date);
    $seconds_left = $date_timestamp - time();
    $minutes_left = ceil($seconds_left / 60);
    $hours_left = floor($minutes_left / 60);
    $minutes_remines = $minutes_left % 60;
    return [$hours_left, $minutes_remines];
}
function validate_length($value, $min, $max)
{
    if ($value) {
        $len = strlen($value);
        if ($len < $min or $len > $max) {
            return "Значение должно быть быть от $min до $max символов";
        }
    } else {
        return "Введите наименование лота";
    }
    return null;
}
function validate_category($value, $allowed_list)
{
    if (!$value) {
        return ("Выберите категорию");
    }
    if (!in_array($value, $allowed_list)) {
        return ("Указана несуществующая категория");
    }
    return null;
}
function validate_message($value)
{
    if (!$value) {
        return ("Опишите лот");
    }
    return null;
}
function validate_rate($value)
{
    if (!$value) {
        return ("Введите начальную цену");
    }
    if (!is_numeric($value) or !($value = +$value) or $value < 1 or is_float($value)) {
        return "Укажите целое число больше нуля";
    }
    return null;
}
function validate_step($value)
{
    if (!$value) {
        return ("Введите шаг ставки");
    }
    if (!is_numeric($value) or !($value = +$value) or $value < 1 or is_float($value)) {
        return "Укажите целое число больше нуля";
    }
    return null;
}
function validate_date($value)
{
    if (!$value) {
        return ("Укажите дату окончания торгов");
    }
    if (!is_date_valid($value)) {
        return "Укажите дату в формате ГГГГ-ММ-ДД";
    }
    if (time() > strtotime($value)) {
        return "$value уже прошло";
    }
    return null;
}
function validate_img()
{
    if (empty($_FILES['lot_img']['name'])) {
        return "Добавьте изображение";
    } else {
        $file_tmp = $_FILES['lot_img']['tmp_name'];
        $file_size = $_FILES['lot_img']['size'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $file_tmp);
        if ($file_type !== 'image/jpeg' && $file_type !== 'image/png') {
            return "Изображение должно иметь формат jpg, или png";
        }
        if ($file_size > 600000) {
            return "Файл должен весить не более 600Кб";
        }
        return null;
    }
}
function validate_email($value, $link)
{
    if (!$value) {
        return ("Введите email");
    }
    if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
        return ("Email введен не корректно");
    }
    $sql = "SELECT COUNT(*) FROM `user` WHERE `email` = ?";
    $email[] = $value;
    $stmt = db_get_prepare_stmt($link, $sql, $email);
    if (!mysqli_stmt_execute($stmt)){
        return ("Ошибка запроса к БД");
    };
    $result = mysqli_stmt_get_result($stmt);
    $result = mysqli_fetch_row($result)[0];
    if($result){
        return ("Пользователь с таким email-ом уже зарегистрирован");
    }
    return null;
}

function validate_password($value)
{
    if (!$value) {
        return ("Введите пароль");
    }
    return null;
}

function validate_name($value)
{
    if (!$value) {
        return ("Введите имя");
    }
    return null;
}
function validate_feedback($value)
{
    if (!$value) {
        return ("Введите контактные данные");
    }
    return null;
}