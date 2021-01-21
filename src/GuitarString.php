<?php

namespace GuitarHero;

class GuitarString
{
    public const SAMPLING_FREQUENCY_RATE = 44100;
    public const ENERGY_DECAY_FACTOR = 0.996;
    public const MIN_NUMBER = -0.5;
    public const MAX_NUMBER = 0.5;

    private RingBuffer $ringBuffer;
    private ?float $headSample = null;

    /**
     * Creates a guitar string of the specified frequency, using a sampling rate of 44,100
     */
    public function createFromFrequency(float $frequency)
    {
        $capacity = round(self::SAMPLING_FREQUENCY_RATE/$frequency);
        $ringBuffer = new RingBuffer($capacity);
        $this->ringBuffer = $ringBuffer;

        return $this;
    }

    /**
     * Creates a guitar string whose length and initial values are given by the specified array
     *
     * @param float[] $init
     */
    public function createFromArray(array $init)
    {
        $ringBuffer = new RingBuffer(sizeof($init));

        for ($i = 0; $i < sizeof($init); $i++) {
            $ringBuffer->enqueue($init[$i]);
        }

        $this->ringBuffer = $ringBuffer;

        return $this;
    }

    /**
     * Returns the number of samples in the ring buffer
     */
    public function length(): int
    {
        return $this->ringBuffer->size();
    }

    /**
     * Plucks this guitar string (by replacing the ring buffer with white noise)
     */
    public function pluck(): void
    {
        for ($i = 0; $i < $this->ringBuffer->capacity(); $i++) {
            $randomValue = mt_rand(self::MIN_NUMBER * 1000000, self::MAX_NUMBER * 1000000) / 1000000;
            $this->ringBuffer->enqueue($randomValue);
        }
    }

    /**
     * Advances the Karplus-Strong simulation one time step
     */
    public function tic(): void
    {
        try {
            $firstSample  = $this->ringBuffer->dequeue();
            $secondSample = $this->ringBuffer->peek();

            $newSample = self::ENERGY_DECAY_FACTOR * 0.5 * ($firstSample + $secondSample);

            $this->ringBuffer->enqueue($newSample);
        } catch (\Exception $exception) {
            echo $exception->getMessage() . PHP_EOL;
        }
    }

    /**
     * Returns the current sample
     */
    public function sample(): ?float
    {
        try {
            return $this->ringBuffer->peek();
        } catch (\Exception $exception) {
            echo $exception->getMessage() . PHP_EOL;
            return null;
        }
    }
}