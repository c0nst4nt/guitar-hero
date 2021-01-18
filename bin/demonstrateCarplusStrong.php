<?php

use GuitarHero\GuitarStringSynthesizer;

require_once './vendor/autoload.php';

// A note (1 octave)
$aNoteFrequency = 440.0;
$synth = new GuitarStringSynthesizer($aNoteFrequency, 1);
$synth->writeToFile(__DIR__  . '/../generated/audio/a_1.wav');
$synth->drawWavePicture('String sound wave (Carplus-Strong)', __DIR__  . '/../generated/images/wave-carplus-strong.png');