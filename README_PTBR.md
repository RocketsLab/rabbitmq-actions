# RabbitMQ Actions

Este pacote é um _wrapper_ de vladimir-yuldashev/rabbitmq-queue-laravel.

Adiciona uma nova maneira de enviar e receber mensagens usando o prorocolo RabbitMQ/AMQP.

### Instalação e requerimentos

---
Requerimentos:

- PHP 8.0 +
- Laravel 8.0 +
- RabbitMQ driver for Laravel Queue 12.0+
---
Da pasta do seu projeto, instalar com composer:

```shell
composer require rocketslab/rabbitmq-actions
```


### Configurar o RabbitMQ driver for Laravel Queue

---
Siga os procedimentos da instalação do pacote `vladimir-yuldashev/rabbitmq-queue-laravel`.

Como configurar o RLQ: [yuldashev/rabbitmq-queue-laravel](https://github.com/vyuldashev/laravel-queue-rabbitmq).

### Publicar o arquivo de configuração

---
```shell
php artisan vendor:publish --provider=RocketsLab\\RabbitMQActions\\RabbitMQActionsServiceProvider
```

O namespace padrão das _actions_ é `App\RabbitMQ\Actions` salvando as _actions_ na pasta
`app/RabbitMQ/Actions`. Você pode mudar isso alterando o namespace no arquivo de configuração.
Por exemplo:

config/rabbitmq-actions.php
```php 
    ...
    'namespace' => 'MyDomain\\Actions'
    ...
```
Quando criar novas _actions_, estas _actions_ serão salvas na pasta `app/MyDomain/Actions` e o
namespace será `App/MyDomain/Actions`.

### Criando Actions

---
A motivação para a criação deste pacote é encontrar uma forma de simplificar
o envio e recebimento de mensagens através do RabbitMQ. Uma das formas que encontrei 
para isso foi usar ações para definir que tipo de mensagem está sendo enviada 
do _producer_ para ser tratada como uma ação pelo _consumer_.

Para criar uma _action_ utilize o comando do artisan `make:rmq-action`.

```shell
php artisan make:rmq-action MyAction
```

A _Action_ será criada como abaixo:

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

O conteúdo dos dados da mensagem é passado no método _handle_ da Action.

As Actions são registradas automaticamente no _container_ do Laravel.
Esse comportamento inicia a _action_ assim que a mensagem é recebida pelo _consumer_.

### Produzindo Mensagens

---
Instalar este pacote e configurar o aplicativo para enviar mensagens como _producer_ é simples.
Esta classe `ProducerMessage` tem um único método chamado `send` que recebe três parâmetros:
`$action`, `$data` e um opcional `$queue`.

O parâmetro *$action* é o nome da _action_, que traduz o nome de uma _action_ no _consumer_.

O parâmetro *$data* são os dados de conteúdo a serem enviados ao _consumer_.

O parâmetro *$queue* é o nome da fila. A fila padrão do Laravel é a `default`.

Ex: `ProduceMessage::send('my-action', 'hello')`, onde `my-action` é a classe `MyAction` 
registrada automaticamente.


### Customizando Actions

---
O nome das _actions_ padrão é o nome da classe no formato **kebab**.

Mas às vezes você quer ter mais controle sobre o nome das _Actions_. 
Para personalizar isso, registre as _Actions_ na opção `custom_actions` no arquivo de configuração.

config/rabbitmq-actions.php
```php
    ...
    'custom_actions' => [
        'hello-action' => MyAction::class
    ];
    ...
```

na aplicação _producer_:
```php
    ProducerMessage::send('hello-action', 'world!');
```

na aplicação _consumer_:
```
    MyAction handle method its called
```

No momento, uma vez que esta opção é usada, o registro automático fica desabilitado, e você deve registrar todas as _Actions_
aqui.

### Roadmap

---
Este é um projeto em andamento. No futuro, mais recursos podem ser criados.

###

---
Obrigado!

**Não esqueça de deixar uma estrela caso eu tenha ajudado de alguma forma ;)**

Jorge <@jjsquady> Gonçalves
