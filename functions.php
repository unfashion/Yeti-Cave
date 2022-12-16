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

function time_left($date){
    $date_timestamp = strtotime($date);
    $seconds_left = $date_timestamp - time();
    $minutes_left = ceil($seconds_left / 60);
    $hours_left = floor($minutes_left / 60);
    $minutes_remines = $minutes_left % 60;
    return [$hours_left, $minutes_remines];
}