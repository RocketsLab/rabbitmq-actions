<?php

namespace RocketsLab\RabbitMQActions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RocketsLab\RabbitMQActions\Contracts\Action;
use RocketsLab\RabbitMQActions\Exceptions\ActionNotFoundException;
use RocketsLab\RabbitMQActions\Exceptions\ActionNotResolvedException;
use RocketsLab\RabbitMQActions\Exceptions\MessageDataNotFoundException;

class ActionMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public array $message){}

    /**
     * Execute the job.
     *
     * @return void
     * @throws ActionNotFoundException
     */
    public function handle()
    {
        if (!isset($this->message['action'])) {
            throw new ActionNotFoundException("An action not found in message payload.");
        }

        if (!isset($this->message['data'])) {
            throw new MessageDataNotFoundException("The data key not found in message payload.");
        }

        try {
            $action = app($this->message['action']);
        }catch (\Exception $exception) {
            throw new ActionNotResolvedException("Failed to resolve action or action not resolvable by container.");
        }

        if (!$action instanceof Action) {
            $className = class_basename($action);
            throw new ActionNotFoundException("Class {$className} not implements RabbitMQ Action interface.");
        }

        $action->handle($this->message['data']);
    }
}
