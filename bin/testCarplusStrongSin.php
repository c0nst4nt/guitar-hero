<?php

use GuitarHero\GuitarString;
use GuitarHero\PlotGenerator;
use Wav\Builder;
use Wav\Sample;
use Wav\WaveFormat;

require_once './vendor/autoload.php';

ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_DEPRECATED);

// A note (4 octave)
$frequency = 440.0;

$generatedSinSmall = [];
for ($i = 0; $i < 100; $i++) {
    $a = 2 * M_PI * $frequency / 44100;
    $generatedSinSmall[] = sin($a * $i);
}

$guitarString = (new GuitarString())->createFromArray($generatedSinSmall);

for ($i = 0; $i < 44100; $i++) {
    $sample = $guitarString->sample() * (32768 / 2);

    $samples[$i << 1] = pack('c', $sample);
    $samples[($i << 1) + 1] = pack('c', $sample >> 8);
    $guitarString->tic();
    $plotData[] = ['', $i, $sample];
}

PlotGenerator::generate(
    $plotData,
    'String sound wave (Carplus-Strong), sinusoid',
    './generated/images/wave-carplus-strong-sin.png',
    -17000
);

$builder = (new Builder())
    ->setAudioFormat(WaveFormat::PCM)
    ->setNumberOfChannels(1)
    ->setSampleRate(Builder::DEFAULT_SAMPLE_RATE)
    ->setByteRate(Builder::DEFAULT_SAMPLE_RATE * 2)
    ->setBlockAlign(2)
    ->setBitsPerSample(16)
    ->setSamples([new Sample(sizeof($samples), implode('', $samples))]);

$audio = $builder->build();
$audio->saveToFile('./generated/audio/a_4_sin.wav');