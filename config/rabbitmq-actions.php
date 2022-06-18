<?php

return  [

    /*
    |--------------------------------------------------------------------------
    | Default Actions Namespace
    |--------------------------------------------------------------------------
    |
    | This option controls the default actions namespacing without application
    | namespace.
    |
    | The default its App\RabbitMQ\Actions
    |
    */
    'namespace' => 'RabbitMQ\\Actions',

    /*
    |--------------------------------------------------------------------------
    | Custom actions definition
    |--------------------------------------------------------------------------
    |
    | The default actions definition its slug all Actions class names as
    | actions names and register this actions into the container.
    |
    | Eg: MyAction, its binded as my-action => MyAction::class
    |
    | You can manually control this behaviour adding in array bellow the
    | action name and action class for each Action created.
    |
    | The default value its an empty array, in this case the auto bind is called.
    |
    */
    'custom_actions' => []

];
