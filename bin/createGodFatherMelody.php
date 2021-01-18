<?php

use GuitarHero\GuitarStringSynthesizer;
use Wav\Builder;
use Wav\Note;
use Wav\Sample;
use Wav\WaveFormat;

require_once './vendor/autoload.php';

ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_DEPRECATED);

$melody = [
    ['E', 3, 0.3],
    ['A', 3, 0.3],
    ['C', 4, 0.3],
    ['H', 3, 0.3],
    ['A', 3, 0.3],
    ['C', 4, 0.3],
    ['A', 3, 0.3],
    ['H', 3, 0.3],
    ['A', 3, 0.3],
    ['F', 3, 0.3],
    ['G', 3, 0.3],
    ['E', 3, 1],
];

$samplesArray = [];
foreach ($melody as $melodyNote) {
    $note = $melodyNote[0];
    $octave = $melodyNote[1];
    $duration = $melodyNote[2];
    $octave = min(8, max(1, $octave));
    $frequency = Note::get($note) * pow(2, $octave - 4);
    $string = (new GuitarStringSynthesizer($frequency, $duration));
    $samples = $string->getSamples();
    $samplesArray[] = new Sample(sizeof($samples), implode('', $samples));
}

$builder = (new Builder())
    ->setAudioFormat(WaveFormat::PCM)
    ->setNumberOfChannels(1)
    ->setSampleRate(Builder::DEFAULT_SAMPLE_RATE)
    ->setByteRate(Builder::DEFAULT_SAMPLE_RATE * 1 * 16 / 8)
    ->setBlockAlign(1 * 16 / 8)
    ->setBitsPerSample(16)
    ->setSamples($samplesArray);

$audio = $builder->build();
$audio->saveToFile(__DIR__  . '/../generated/audio/god_father.wav');