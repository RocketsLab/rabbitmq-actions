<?php

namespace RocketsLab\RabbitMQActions\Contracts;

interface Action
{
    /**
     * Handle the message data
     *
     * @param mixed $data
     * @return mixed
     */
    public function handle(mixed $data);
}
