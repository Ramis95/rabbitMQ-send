<?php
require_once __DIR__ . '/vendor/autoload.php';

//Подключаем RabbitMQ
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'user1', '123');
$channel    = $connection->channel();

$channel->queue_declare('MyQueue', false, true, false, false); // Объявляем очередь, если очередь объявлена то ничего не происходит

echo " [*] Waiting for messages. To exit press CTRL+C\n";
$callback = function ($msg) {
    echo ' [x] Тело сообщения ', $msg->body, "\n";
};
$channel->basic_consume('MyQueue', '', false, true, false, false, $callback);

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();
?>
