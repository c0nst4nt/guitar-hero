<?php

use GuitarHero\GuitarStringSynthesizer;

require_once './vendor/autoload.php';

ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_DEPRECATED);

// A note (4 octave)
$aNoteFrequency = 440.0;
$synth = new GuitarStringSynthesizer($aNoteFrequency, 2);
$synth->writeToFile(__DIR__  . '/../generated/audio/a_4.wav');
$synth->drawWavePicture('String sound wave (Carplus-Strong)', __DIR__  . '/../generated/images/wave-carplus-strong.png');