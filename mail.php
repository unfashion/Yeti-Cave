<?php

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

require 'vendor/autoload.php';
function send_message($link, $lot_id)
{
    // Получаем необходимую информацию о пбедителе, лоте и продавце
    $sql = "SELECT winner.name AS winner_name, 
    winner.email AS winner_email, 
    seller.name AS seller_name, 
    seller.email AS seller_email, 
    seller.contacts AS seller_contacts, 
    lot.name AS lot_name FROM lot 
    JOIN user AS seller ON lot.author_id = seller.id 
    JOIN user AS winner ON lot.winner_id = winner.id  
    WHERE lot.id = $lot_id";
    $data = mysqli_query($link, $sql);
    $data = mysqli_fetch_assoc($data);

    // Конфигурация траспорта
    $dsn = 'smtp://yeti@unfashion.ru:ZttbgeuzMnFn9HgJu9Uq@smtp.mail.ru:465';
    $transport = Transport::fromDsn($dsn);

    // Формирование сообщения
    $message = new Email();
    $message->to($data['winner_email']);
    $message->from("yeti@unfashion.ru");
    $message->subject("Хорошие новости из пещеры йети");
    $message->text($data['winner_name'] . ", приветствую! Твоя последняя ставка победила. Лот называется \"" . $data['lot_name'] . "\". Свяжись с продавцом по имени " . $data['seller_name'] . ". Его email - " . $data['seller_email'] . ". А это дополнительная контактная информация, которую он о себе оставил: " . $data['seller_contacts'] );
    
    // Отправка сообщения
    $mailer = new Mailer($transport);
    $mailer->send($message);
    print_r($data);
}
