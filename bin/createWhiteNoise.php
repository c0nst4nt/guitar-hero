<?php

use GuitarHero\PlotGenerator;

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

    $generatedNoise[] = ['', $i, $sample];

    $samples[$i << 1] = pack('c', $sample);
    $samples[($i << 1) + 1] = pack('c', $sample >> 8);
}

PlotGenerator::generate($generatedNoise, 'White noise, long', './generated/images/white_noise_example_long.png', -17768);

$samplesString = join('', $samples);

$handle = fopen('./generated/audio/white_noise.wav', 'wb');

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