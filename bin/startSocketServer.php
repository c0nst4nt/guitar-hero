<?php

require_once './vendor/autoload.php';

use React\Socket\Server as SocketServer;
use React\Http\Server;
use React\Http\Message\Response;
use React\EventLoop\Factory;
use Psr\Http\Message\ServerRequestInterface;
use React\Stream\ReadableResourceStream;

$loop = Factory::create();

$server = new Server(
    $loop,
    function (ServerRequestInterface $request) use ($loop) {
        $audioFile = new ReadableResourceStream(
            fopen('./generated/audio/notes/' . $request->getQueryParams()['note'] . '.wav', 'r'), $loop
        );

        return new Response(
            200, ['Content-Type' => 'audio/x-wav'], $audioFile
        );
    }
);

$socket = new SocketServer('127.0.0.1:8000', $loop);

$server->listen($socket);

echo 'Listening on '
    . str_replace('tcp:', 'http:', $socket->getAddress())
    . "\n";

$loop->run();