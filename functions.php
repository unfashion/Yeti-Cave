<?php

/**
 * Price formatting
 * @param int $price - start price
 * @return string - formatted price
 */
function price_format($price)
{
    $formatted_price = number_format($price, 0, '.', ' ');
    return $formatted_price;
}
function bet_time_format($time){
    $date = strtotime($time);
    $today = strtotime('today');
    $yesterday = strtotime('yesterday');
    $sec_passed = time() - $date;
    $min_passed = floor($sec_passed / 60);
    $hours_passed = floor($min_passed / 60);
    if ($sec_passed <= 1) return "только-что";
    if ($min_passed < 1) return $sec_passed . " " . get_noun_plural_form($sec_passed, "секунда", "секунды", "секунд") . " назад";
    if ($hours_passed < 1) return $min_passed . " " . get_noun_plural_form($min_passed, "минута", "минуты", "минут") . " назад";
    if ($hours_passed == 1) return "час назад";
    if ($hours_passed > 1 && $date > $today) return $hours_passed . " " . get_noun_plural_form($hours_passed, "час", "часа", "часов") . " назад";
    if ($date < $today && $date > $yesterday) return date('вчера в H:i', $date);
    return date('d.m.y в H:i', $date); 
}
function time_left($date)
{
    $end_date_timestamp = strtotime($date);
    $seconds = $end_date_timestamp - time();
    $hours = floor($seconds / 3600);
    $minutes = floor($seconds % 3600 / 60);
    $hours_pad = str_pad((string)$hours, 2, '0', STR_PAD_LEFT);
    $minutes_pad = str_pad((string)$minutes, 2, '0', STR_PAD_LEFT);
    $seconds_pad = str_pad((string)$seconds, 2, '0', STR_PAD_LEFT);
    //echo date('H:i:s');
    return [
        'hours' => $hours_pad, 
        'minutes' => $minutes_pad,
        'seconds' => $seconds_pad,
        'is_fire' => $hours < 1 ? true : false,
        'is_end' => $seconds < 1 ? true : false,
    ];
    
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
    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
        return ("Email введен не корректно");
    }
    $sql = "SELECT COUNT(*) FROM `user` WHERE `email` = ?";
    $email[] = $value;
    $stmt = db_get_prepare_stmt($link, $sql, $email);
    if (!mysqli_stmt_execute($stmt)) {
        return ("Ошибка запроса к БД");
    };
    $result = mysqli_stmt_get_result($stmt);
    $result = mysqli_fetch_row($result)[0];
    if ($result) {
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

function validate_login_email($value, $link)
{
    if (!$value) {
        return "Введите email";
    } else if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
        return "Email введен не корректно";
    } else {
        return null;
    }
}

function validate_bet($bet, $min_bet)
{
    if(!$bet){
        return "Укажите ставку";
    }
    if (!filter_var($bet, FILTER_VALIDATE_INT)) {
        return "Ставка должна быть целым числом";
    }
    if (!isset($_SESSION['id'])){
        return "Войдите в свою учетную запись, чтобы сделать ставку";
    }
    if ($bet < $min_bet){
        return "Ставка не должна быть ниже минимальной";
    }
    return null;
}
