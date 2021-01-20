<?php

use GuitarHero\PlotGenerator;
use Wav\Builder;
use Wav\Sample;
use Wav\WaveFormat;

require_once './vendor/autoload.php';

ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_DEPRECATED);

$generatedNoiseSmall = [];
for ($i = 0; $i < 100; $i++) {
    $generatedNoiseSmall[] = ['', $i, mt_rand(-0.5 * 1000000, 0.5 * 1000000) / 1000000];
}

PlotGenerator::generate($generatedNoiseSmall, 'White noise, small', './generated/images/white_noise_example_short.png', -0.5);

$generatedNoise = [];
for ($i = 0; $i < 44100; $i++) {
    $sample = mt_rand(-0.5 * 1000000, 0.5 * 1000000) / 1000000 * 32768;
    $samples[] = $sample;
    $generatedNoise[] = ['', $i, $sample];
}

PlotGenerator::generate($generatedNoise, 'White noise, long', './generated/images/white_noise_example_long.png', -17768);

$sample = new Sample(sizeof($samples), implode('', $samples));

$builder = (new Builder())
    ->setAudioFormat(WaveFormat::PCM)
    ->setNumberOfChannels(1)
    ->setSampleRate(Builder::DEFAULT_SAMPLE_RATE)
    ->setByteRate(Builder::DEFAULT_SAMPLE_RATE * 1 * 16 / 8)
    ->setBlockAlign(1 * 16 / 8)
    ->setBitsPerSample(16)
    ->setSamples([$sample]);

$audio = $builder->build();
$audio->saveToFile('./generated/audio/white_noise.wav');