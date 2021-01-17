<?php

namespace GuitarHero;

use Binary\Helper;
use Wav\Builder;
use Wav\Sample;
use Wav\WaveFormat;

class GuitarStringSynthesizer
{
    private GuitarString $guitarString;
    private float $duration;
    private ?array $generatedSamples = null;
    private ?array $plotData = null;
    private float $minSampleValue = Builder::DEFAULT_VOLUME;

    public function __construct(float $frequency, float $duration)
    {
        $this->guitarString = (new GuitarString())->createFromFrequency($frequency);
        $this->duration     = $duration;
    }

    public function writeToFile(string $filename)
    {
        $sample = new Sample(sizeof($this->getSamples()), implode('', $this->getSamples()));

        $builder = (new Builder())
            ->setAudioFormat(WaveFormat::PCM)
            ->setNumberOfChannels(1)
            ->setSampleRate(Builder::DEFAULT_SAMPLE_RATE)
            ->setByteRate(Builder::DEFAULT_SAMPLE_RATE * 1 * 16 / 8)
            ->setBlockAlign(1 * 16 / 8)
            ->setBitsPerSample(16)
            ->setSamples([$sample]);

        $audio = $builder->build();
        $audio->saveToFile($filename);
    }

    public function drawWavePicture(string $plotName, string $filename)
    {
        $plot = new \PHPlot(800, 600);
        $plot->SetImageBorderType('plain');
        $plot->SetPlotType('lines');
        $plot->SetDataType('data-data');
        $plot->SetDataValues($this->plotData);
        $plot->SetTitle($plotName);
        $plot->SetPlotAreaWorld(NULL, $this->minSampleValue, NULL, NULL);
        $plot->SetIsInline(true);
        $plot->SetOutputFile($filename);
        $plot->DrawGraph();
    }

    public function getSamples(): array
    {
        if ($this->generatedSamples === null) {
            $samples = [];
            $duration = Builder::DEFAULT_SAMPLE_RATE * $this->duration;

            $this->guitarString->pluck();

            for ($i = 0; $i < $duration; $i++) {
                $sample = $this->guitarString->sample() * Builder::DEFAULT_VOLUME;

                if ($sample < $this->minSampleValue) {
                    $this->minSampleValue = $sample;
                }

                $samples[$i << 1] = Helper::packChar($sample);
                $samples[($i << 1) + 1] = Helper::packChar($sample >> 8);
                $this->guitarString->tic();
                $this->plotData[] = ['', $i, $sample];
            }

            $this->generatedSamples = $samples;
        }

        return $this->generatedSamples;
    }

    public function getPlotData()
    {
        return $this->plotData;
    }
}