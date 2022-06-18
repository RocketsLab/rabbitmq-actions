# RabbitMQ Actions

This package its a wrapper of vladimir-yuldashev/rabbitmq-queue-laravel.

Adds a new feature to produce and consume messages with RabbitMQ/AMQP protocol.

[Versão em PT-BR](README_PTBR.md)

### Install and Requirements

---
Requirements:

- PHP 8.0 +
- Laravel 8.0 +
- RabbitMQ driver for Laravel Queue 12.0+

Install this package with composer:

```shell
composer require rocketslab/rabbitmq-actions
```


### Configure RabbitMQ driver for Laravel Queue

---
Follow `vladimir-yuldashev/rabbitmq-queue-laravel` installation.

How to configure in RLQ documentation [yuldashev/rabbitmq-queue-laravel](https://github.com/vyuldashev/laravel-queue-rabbitmq).

### Publish the configuration

---
```shell
php artisan vendor:publish --provider=RocketsLab\\RabbitMQActions\\RabbitMQActionsServiceProvider
```

The default action namespace its `App\RabbitMQ\Actions` saving actions at `app/RabbitMQ/Actions` folder.
You can change this setting the `namespace` config. For example:

config/rabbitmq-actions.php
```php 
    ...
    'namespace' => 'MyDomain\\Actions'
    ...
```
When creating new actions, this actions are save at `app/MyDomain/Actions` directory and your
actions namespace its changed to `App\MyDomain\Actions`.

### Creating Actions

---
The motivation for creating this package is to find a way to simplify sending and receiving messages through RabbitMQ.
One of the ways I found for this was to use actions to define what type of message is being sent from the producer to be treated as an action by the consumer.

To create an action use the artisan `make:rmq-action` command.

```shell
php artisan make:rmq-action MyAction
```

The action file looks like this:

```php
namespace App\RabbitMQ\Actions;

use RocketsLab\RabbitMQActions\Contracts\Action;

class MyAction implements Action
{
    /**
     * Handles the message data
     *
     * @param mixed $data
     *
     */
    public function handle(mixed $data)
    {
        // Process the message data from broker
    }
}
```

The message data content its passed at the Action handle method. 

Actions are automatically registered into the Laravel container.
This behavior launches the action as soon as the message is received by the consumer.

### Producing Messages

---
Installing this package and configuring the application to send messages as a producer is simple.
This package class `ProducerMessage` have a single method called `send` that takes three parameters: `$action`, `$data` and an optional `$queue`.

The *action* parameter is the name of action, that translates the name of an action in consumer application.

The *data* parameter is the content data to be send to consumer.

The *queue* parameter is the name of queue. Default is Laravel queue `default`.

Eg: `ProduceMessage::send('my-action', 'hello')`, the `my-action` its the `MyAction` class registered automatically by consumer.


### Custom actions 

---
The default actions name its the name of action Class in **kebab** form. 

But sometimes you want to have more control over the name of actions. To customize the actions "keys", 
register this in `custom_actions` option at config file.

config/rabbitmq-actions.php
```php
    ...
    'custom_actions' => [
        'hello-action' => MyAction::class
    ];
    ...
```

in the producer application:
```php
    ProducerMessage::send('hello-action', 'world!');
```

in the consumer application:
```
    MyAction handle method its called
```

At moment, once this option is used, the automatic registration its disabled, and you have to register all actions
here.

### Roadmap

---
This is a work in progress project. In the future more features can be created.

###

---
Thank you! 

**Don`t forget to leave a star here if I help in any way ;)** 

Jorge <@jjsquady> Gonçalves
