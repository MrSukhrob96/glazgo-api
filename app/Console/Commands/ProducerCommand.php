<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ProducerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rmq:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->exchange_declare('hello', 'fanout', false, false, false);
        
        $channel->queue_declare('hello', false, false, false, false);
        $channel->queue_bind("hello", 'hello');

        $msg = new AMQPMessage('Hello World! How are you?');
        $channel->basic_publish($msg, '', 'hello');

        $channel->close();
        $connection->close();
    }
}
