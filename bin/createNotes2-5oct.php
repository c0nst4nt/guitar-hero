<?php

use GuitarHero\GuitarStringSynthesizer;
use Wav\Builder;
use Wav\Note;
use Wav\Sample;
use Wav\WaveFormat;

require_once './vendor/autoload.php';

ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_DEPRECATED);

$notes = [
    ['C',  2], ['C#', 2], ['D',  2], ['D#', 2], ['E',  2], ['F',  2], ['F#', 2], ['G',  2], ['G#', 2], ['A',  2], ['A#', 2], ['H',  2],
    ['C',  3], ['C#', 3], ['D',  3], ['D#', 3], ['E',  3], ['F',  3], ['F#', 3], ['G',  3], ['G#', 3], ['A',  3], ['A#', 3], ['H',  3],
    ['C',  4], ['C#', 4], ['D',  4], ['D#', 4], ['E',  4], ['F',  4], ['F#', 4], ['G',  4], ['G#', 4], ['A',  4], ['A#', 4], ['H',  4],
    ['C',  5], ['C#', 5], ['D',  5], ['D#', 5], ['E',  5], ['F',  5], ['F#', 5], ['G',  5], ['G#', 5], ['A',  5], ['A#', 5], ['H',  5],
];

$startTime = new DateTime('now');

$samplesArray = [];
foreach ($notes as $chromaticNote) {
    $note      = $chromaticNote[0];
    $octave    = $chromaticNote[1];
    $octave    = min(8, max(1, $octave));

    $frequency = Note::get($note) * pow(2, $octave - 4);
    $string    = (new GuitarStringSynthesizer($frequency, 1));

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

    // Converting note names to different system
    if ($note === 'H') {
        $note = 'B';
    }

    $audio->saveToFile(__DIR__  . '/../generated/audio/notes/' . $note . '_' . $octave .'.wav');
}

$endTime = new DateTime('now');
$interval = $startTime->diff($endTime);
echo $interval->format('%s.%f s') . PHP_EOL;