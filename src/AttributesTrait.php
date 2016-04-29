<?php

declare(strict_types=1);

namespace byrokrat\billing;

/**
 * Standard implementation of setAttribute and getAttribute
 */
trait AttributesTrait
{
    /**
     * @var array Loaded attributes
     */
    private $attributes = [];

    /**
     * Set attribute defined by key
     */
    public function setAttribute(string $key, $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * Get attribute definied by key, returning default if attribute is not set
     *
     * @return mixed
     */
    public function getAttribute(string $key, $default = '')
    {
        return $this->attributes[$key] ?? $default;
    }

    /**
     * Get all loaded attributes
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Clear all attributes
     */
    public function clearAttributes(): self
    {
        $this->attributes = [];
        return $this;
    }
}
