<?php

namespace RocketsLab\RabbitMQActions;

use RocketsLab\RabbitMQActions\Contracts\Message;

class ProducerMessage implements Message
{
    /**
     * Sends a message to broker with a specific action-name
     *
     * @param string $action
     * @param mixed $data
     * @param string $queue
     * @return mixed
     */
    public static function send(string $action, mixed $data, string $queue = "default")
    {
        ActionMessageJob::dispatch(['action' => $action, 'data' => $data])->onQueue($queue);
    }
}
