<?php

require_once './vendor/autoload.php';

use React\Socket\Server as SocketServer;
use React\Http\Server;
use React\Http\Message\Response;
use React\EventLoop\Factory;
use Psr\Http\Message\ServerRequestInterface;
use React\Stream\ReadableResourceStream;


//$sampleBuilder = new \Wav\SampleBuilder(\Wav\Generator\AcousticGuitar::NAME);
//
//$samples = [
//    $sampleBuilder->note('E', 5, 0.3),
//    $sampleBuilder->note('D#', 5, 0.3),
//    $sampleBuilder->note('E', 5, 0.3),
//    $sampleBuilder->note('D#', 5, 0.3),
//    $sampleBuilder->note('E', 5, 0.3),
//    $sampleBuilder->note('H', 4, 0.3),
//    $sampleBuilder->note('D', 5, 0.3),
//    $sampleBuilder->note('C', 5, 0.3),
//    $sampleBuilder->note('A', 4, 1),
//];
//
//$builder = (new Wav\Builder())
//    ->setAudioFormat(\Wav\WaveFormat::PCM)
//    ->setNumberOfChannels(1)
//    ->setSampleRate(\Wav\Builder::DEFAULT_SAMPLE_RATE)
//    ->setByteRate(\Wav\Builder::DEFAULT_SAMPLE_RATE * 1 * 16 / 8)
//    ->setBlockAlign(1 * 16 / 8)
//    ->setBitsPerSample(16)
//    ->setSamples($samples);
//
//$audio = $builder->build();

//$server = new Server(
//    $loop,
//    function (ServerRequestInterface $request) use ($loop, $audio) {
////        $video = new ReadableResourceStream(
////            fopen('./generated/audio/test.wav', 'r'), $loop
////        );
////
////        return new Response(
////            200, ['Content-Type' => 'audio/x-wav'], $video
////        );
//
//        $audio->returnContent();
//    });

$loop = Factory::create();

$video = new ReadableResourceStream(
    fopen('./generated/audio/test.wav', 'r'), $loop
);

$video->on('data', function(){
    echo "Reading file\n";
});

$server = new Server(
    $loop,
    function (ServerRequestInterface $request) use ($video) {
        return new Response(
            200, ['Content-Type' => 'audio/x-wav'], $video
        );
    });
$socket = new SocketServer('127.0.0.1:8000', $loop);

$server->listen($socket);

echo 'Listening on '
    . str_replace('tcp:', 'http:', $socket->getAddress())
    . "\n";

$loop->run();