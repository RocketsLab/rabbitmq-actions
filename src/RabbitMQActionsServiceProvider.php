<?php

namespace RocketsLab\RabbitMQActions;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use ReflectionClass;
use RocketsLab\RabbitMQActions\Commands\ActionMakeCommand;
use Symfony\Component\Finder\Finder;

class RabbitMQActionsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->mergeConfigFrom(__DIR__ . "/../config/rabbitmq-actions.php", "rabbitmq-actions");

        // Register all actions into the container
        empty(config('rabbitmq-actions.custom_actions')) ?
            $this->automaticActionsBind() :
            $this->customActionsBind();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootCommands();

        $this->publishesConfig();
    }

    protected function bootCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ActionMakeCommand::class,
            ]);
        }
    }

    protected function publishesConfig()
    {
        $this->publishes([
            __DIR__ . "/../config/rabbitmq-actions.php" => config_path('rabbitmq-actions.php')
        ], 'rmq-actions-config');
    }

    /**
     * Automatically register actions
     *
     * @throws \ReflectionException
     */
    protected function automaticActionsBind()
    {
        $actionsNamespace = ltrim(config('rabbitmq-actions.namespace'), "\\");
        $actionsFolder = app_path(Str::replace("\\", "/", $actionsNamespace));

        if (! file_exists($actionsFolder)) {
            return;
        }

        $appNamespace = rtrim($this->app->getNamespace(), "\\");

        foreach ((new Finder)->in($actionsFolder)->files() as $action) {
            $fileName = $action->getFilenameWithoutExtension();
            $actionName = Str::kebab($fileName);
            $className = "{$appNamespace}\\{$actionsNamespace}\\{$fileName}";
            $this->app->bind($actionName, (new ReflectionClass($className))->getName());
        }
    }

    /**
     * Register the actions set in custom-actions configuration
     *
     * @return void
     */
    protected function customActionsBind()
    {
        foreach (config('rabbitmq-actions.custom_actions') as $actionKey => $actionClass) {
            $this->app->bind($actionKey, $actionClass);
        }
    }
}
