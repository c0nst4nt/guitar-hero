<?php

use GuitarHero\GuitarStringSynthesizer;
use Wav\Builder;
use Wav\Note;
use Wav\Sample;
use Wav\WaveFormat;

require_once './vendor/autoload.php';

ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_DEPRECATED);

$notes = [
    ['C',  2, 1],
    ['C#', 2, 1],
    ['D',  2, 1],
    ['D#', 2, 1],
    ['E',  2, 1],
    ['F',  2, 1],
    ['F#', 2, 1],
    ['G',  2, 1],
    ['G#', 2, 1],
    ['A',  2, 1],
    ['A#', 2, 1],
    ['B',  2, 1],
    ['C',  3, 1],
    ['C#', 3, 1],
    ['D',  3, 1],
    ['D#', 3, 1],
    ['E',  3, 1],
    ['F',  3, 1],
    ['F#', 3, 1],
    ['G',  3, 1],
    ['G#', 3, 1],
    ['A',  3, 1],
    ['A#', 3, 1],
    ['B',  3, 1],
    ['C',  4, 1],
    ['C#', 4, 1],
    ['D',  4, 1],
    ['D#', 4, 1],
    ['E',  4, 1],
    ['F',  4, 1],
    ['F#', 4, 1],
    ['G',  4, 1],
    ['G#', 4, 1],
    ['A',  4, 1],
    ['A#', 4, 1],
    ['B',  4, 1],
    ['C',  5, 1],
    ['C#', 5, 1],
    ['D',  5, 1],
    ['D#', 5, 1],
    ['E',  5, 1],
    ['F',  5, 1],
    ['F#', 5, 1],
    ['G',  5, 1],
    ['G#', 5, 1],
    ['A',  5, 1],
    ['A#', 5, 1],
    ['B',  5, 1],
];

$samplesArray = [];
foreach ($notes as $chromaticNote) {
    $note      = $chromaticNote[0];
    $octave    = $chromaticNote[1];
    $duration  = $chromaticNote[2];
    $octave    = min(8, max(1, $octave));
    $frequency = Note::get($note) * pow(2, $octave - 4);
    $string    = (new GuitarStringSynthesizer($frequency, $duration));
    $samples   = $string->getSamples();
    $noteSample = new Sample(sizeof($samples), implode('', $samples));

    $builder = (new Builder())
        ->setAudioFormat(WaveFormat::PCM)
        ->setNumberOfChannels(1)
        ->setSampleRate(Builder::DEFAULT_SAMPLE_RATE)
        ->setByteRate(Builder::DEFAULT_SAMPLE_RATE * 1 * 16 / 8)
        ->setBlockAlign(1 * 16 / 8)
        ->setBitsPerSample(16)
        ->setSamples([$noteSample]);

    $audio = $builder->build();
    $audio->saveToFile(__DIR__  . '/../generated/audio/notes/' . $note . '_' . $octave .'.wav');
}
