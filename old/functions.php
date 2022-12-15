<?php 
function price_formatting($price){
    $price = ceil($price);
    $price = number_format($price, 0, '', ' ');
    $price .= ' <b class="rub">р</b>';
    return $price;
}