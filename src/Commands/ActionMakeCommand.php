<?php

namespace RocketsLab\RabbitMQActions\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class ActionMakeCommand extends GeneratorCommand
{
    protected $name = "make:rmq-action";

    protected $description = "Make a new RabbitMQ action.";

    protected $type = 'Action';

    protected function getStub()
    {
        return __DIR__ . "/../../stubs/action.stub";
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $actionsNamespace = ltrim(config('rabbitmq-actions.namespace'), "\\");
        $actionsFolder = app_path(Str::replace("\\", "/", $actionsNamespace));

        // Check if actions folder not exists and create it
        if (!file_exists($actionsFolder)) {
            mkdir(directory: $actionsFolder, recursive: true);
        }

        return is_dir($actionsFolder) ? "$rootNamespace\\$actionsNamespace" : $rootNamespace;
    }
}
