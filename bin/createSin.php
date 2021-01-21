<?php

use GuitarHero\PlotGenerator;

require_once './vendor/autoload.php';

$frequency = 440;

$generatedSinSmall = [];
for ($i = 0; $i < 100; $i++) {
    $a = 2 * M_PI * $frequency / 44100;
    $sample = sin($a * $i);
    $generatedSinSmall[] = ['', $i, $sample];
}

PlotGenerator::generate($generatedSinSmall, 'Sinusoid, small', './generated/images/sinusoid_small.png', -1);

$generatedSin = [];
for ($i = 0; $i < 44100; $i++) {
    $a = 2 * M_PI * $frequency / 44100;
    $sample = sin($a * $i) * 32768;

    $generatedSin[] = ['', $i, $sample];

    $samples[$i << 1] = pack('c', $sample);
    $samples[($i << 1) + 1] = pack('c', $sample >> 8);
}

PlotGenerator::generate($generatedSin, 'Sinusoid', './generated/images/sinusoid.png', -32768);

$samplesString = join('', $samples);

$handle = fopen('./generated/audio/sin.wav', 'wb');

fwrite($handle, pack('a*', 'RIFF')); // RIFF (symbols)
fwrite($handle, pack('V', 40 + strlen($samplesString))); // chunk size
fwrite($handle, pack('a*', 'WAVE')); // WAVE (symbols)
fwrite($handle, pack('a*', 'fmt ')); // 'fmt ' (symbols)
fwrite($handle, pack('V', 16)); // subchunkSize (16 - for PCM)
fwrite($handle, pack('v', 0x0001)); // audioFormat (Microsoft WAV PCM)
fwrite($handle, pack('v', 1)); // numChannels (1 - mono)
fwrite($handle, pack('V', 44100)); // sampleRate
fwrite($handle, pack('V', 44100 * 2)); // bytes per second (byteRate)
fwrite($handle, pack('v', 2)); // blockAlign (bytes in one sample)
fwrite($handle, pack('v', 16)); // bitsPerSample
fwrite($handle, pack('a*', 'DATA')); // data (symbols)
fwrite($handle, pack('V', strlen($samplesString))); // chunk size
fwrite($handle, pack('a*', $samplesString)); // samples

fclose($handle);