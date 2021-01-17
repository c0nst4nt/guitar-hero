<?php

use GuitarHero\GuitarStringSynthesizer;
use Wav\Note;
use Wav\Sample;

require_once './vendor/autoload.php';

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
$plotData = [];
foreach ($melody as $melodyNote) {
    $note = $melodyNote[0];
    $octave = $melodyNote[1];
    $duration = $melodyNote[2];
    $octave = min(8, max(1, $octave));
    $frequency = Note::get($note) * pow(2, $octave - 4);
    $string = (new GuitarStringSynthesizer($frequency, $duration));
    $samples = $string->getSamples();
    $samplesArray[] = new Sample(sizeof($samples), implode('', $samples));
    $plotData = array_merge($string->getPlotData(), $plotData);
}

$plot = new \PHPlot(sizeof($plotData), 600);
$plot->SetImageBorderType('plain');
$plot->SetPlotType('lines');
$plot->SetDataType('data-data');
$plot->SetDataValues($plotData);
$plot->SetTitle('Melody wave');
$plot->SetPlotAreaWorld(NULL, -\Wav\Builder::DEFAULT_VOLUME, NULL, NULL);
$plot->SetIsInline(true);
$plot->SetOutputFile(__DIR__  . '/../generated/images/god_father.png');
$plot->DrawGraph();

$builder = (new Wav\Builder())
    ->setAudioFormat(\Wav\WaveFormat::PCM)
    ->setNumberOfChannels(1)
    ->setSampleRate(\Wav\Builder::DEFAULT_SAMPLE_RATE)
    ->setByteRate(\Wav\Builder::DEFAULT_SAMPLE_RATE * 1 * 16 / 8)
    ->setBlockAlign(1 * 16 / 8)
    ->setBitsPerSample(16)
    ->setSamples($samplesArray);

$audio = $builder->build();
$audio->saveToFile(__DIR__  . '/../generated/audio/god_father.wav');

//$aNoteFrequency = 440.0;

//$synth = new GuitarStringSynthesizer($aNoteFrequency, 1);
//$synth->writeToFile(__DIR__  . '/../generated/audio/test.wav');
//$synth->drawWavePicture('String sound wave (Carplus-Strong)', __DIR__  . '/../generated/images/wave-carplus-strong.png');

//$init = [0.2,0.4,0.5,0.3,-0.2,0.4,0.3,0.0,-0.1,-0.3];
//$gs = (new GuitarString())->createFromArray($init);
//
//for ($i = 0; $i < $gs->length(); $i++) {
//    $gs->tic();
//}
//
//var_dump($gs);
//die;

