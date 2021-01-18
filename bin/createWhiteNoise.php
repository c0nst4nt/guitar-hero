<?php

use Wav\Builder;
use Wav\Sample;
use Wav\WaveFormat;

require_once './vendor/autoload.php';

ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_DEPRECATED);

$generatedNoiseSmall = [];
for ($i = 0; $i < 100; $i++) {
    $generatedNoiseSmall[] = ['', $i, mt_rand(-0.5 * 1000000, 0.5 * 1000000) / 1000000];
}

$plot = new \PHPlot(800, 600);
$plot->SetImageBorderType('plain');
$plot->SetPlotType('lines');
$plot->SetDataType('data-data');
$plot->SetDataValues($generatedNoiseSmall);
$plot->SetTitle('');
$plot->SetPlotAreaWorld(NULL, -0.5, NULL, NULL);
$plot->SetIsInline(true);
$plot->SetOutputFile('./generated/images/white_noise_example_short.png');
$plot->DrawGraph();

$generatedNoise = [];
for ($i = 0; $i < 44100; $i++) {
    $sample = mt_rand(-0.5 * 1000000, 0.5 * 1000000) / 1000000 * 32768;
    $samples[] = $sample;
    $generatedNoise[] = ['', $i, $sample];
}

$plot = new \PHPlot(800, 600);
$plot->SetImageBorderType('plain');
$plot->SetPlotType('lines');
$plot->SetDataType('data-data');
$plot->SetDataValues($generatedNoise);
$plot->SetTitle('');
$plot->SetPlotAreaWorld(NULL, -17768, NULL, NULL);
$plot->SetIsInline(true);
$plot->SetOutputFile('./generated/images/white_noise_example_long.png');
$plot->DrawGraph();

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