<?php

namespace GuitarHero;

use RuntimeException;

class RingBuffer
{
    private int $capacity;
    private array $storage = [];

    /**
     * Creates an empty ring buffer with the specified capacity
     */
    public function __construct(int $capacity)
    {
        $this->capacity = $capacity;
    }

    /**
     * Returns the capacity of this ring buffer
     */
    public function capacity(): int
    {
        return $this->capacity;
    }

    /**
     * Returns the number of items currently in this ring buffer
     */
    public function size(): int
    {
        return sizeof($this->storage);
    }

    /**
     * Is this ring buffer empty (size equals zero)?
     */
    public function isEmpty(): bool
    {
        return sizeof($this->storage) === 0;
    }

    /**
     * Is this ring buffer full (size equals capacity)?
     */
    public function isFull(): bool
    {
        return $this->size() === $this->capacity();
    }

    /**
     * Adds item x to the end of this ring buffer
     */
    public function enqueue(float $x): void
    {
        if ($this->isFull()) {
            throw new RuntimeException('Ring buffer is full');
        }

        $this->storage[] = $x;
    }

    /**
     * Deletes and returns the item at the front of this ring buffer
     */
    public function dequeue(): float
    {
        if ($this->isEmpty()) {
            throw new RuntimeException('Ring buffer is empty');
        }

        return array_shift($this->storage);
    }

    /**
     * Returns the item at the front of this ring buffer
     */
    public function peek(): float
    {
        if ($this->isEmpty()) {
            throw new RuntimeException('Ring buffer is empty');
        }

        return $this->storage[0];
    }
}