<?php


require __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

$exchange = 'test_swoole';
$queue = 'test_swoole';
$consumerTag = 'consumer';


$connection = new AMQPStreamConnection(
    '127.0.0.1', '5672', 'guest', 'guest'
);

$channel = $connection->channel();

$channel->queue_declare($queue, false, true, false, false);
$channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);
$channel->queue_bind($queue, $exchange);

$channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);

$channel->queue_bind($queue, $exchange);

$messageBody = '{"one": 1}';
$message = new AMQPMessage($messageBody, array('content_type' => 'text/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
$channel->basic_publish($message, $exchange);

$channel->close();
$connection->close();

register_shutdown_function('shutdown', $channel, $connection);


function shutdown($channel, $connection)
{
    $channel->close();
    $connection->close();
}