<?php

namespace controllers;

use core\Rabbit;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitController extends Rabbit
{
    function __construct()
    {
        parent::__construct();
    }

    public function add_message($msg)
    {

        $channel = $this->RabbitConnection->channel(); // Channel - Виртуальное соединение внутри соединения. Когда публикуем или получаете сообщения через очередь – это все делается в канале.

        //Берем канал и декларируем в нем новую очередь, первый аргумент - название (Создаем очередь с такими параметрами, если очередь уже есть то ничего не произойдет)
        $channel->queue_declare('MyQueue', false, true, false, false);

        //queue name - Имя очереди может содержать до 255 байт UTF-8 символов
        //false     passive - может использоваться для проверки того, инициирован ли обмен, без того, чтобы изменять состояние сервера
        //true      durable - убедимся, что RabbitMQ никогда не потеряет очередь при падении - очередь переживёт перезагрузку брокера
        //false     exclusive - используется только одним соединением, и очередь будет удалена при закрытии соединения
        //false      autodelete - очередь удаляется, когда отписывается последний подписчик


        //Создаем новое сообщение
        $msg = new AMQPMessage($msg, ['delivery_mode' => 2]);
        //'delivery_mode' => 2    создаёт сообщение постоянным, чтобы оно не потерялось при падении или закрытии сервера

        //Отправляем его в очередь
        $channel->basic_publish($msg, '', 'MyQueue'); //exchane '' точка обмена по умолчанию

        $channel->close();
        $this->RabbitConnection->close();

        return true;

    }
}
