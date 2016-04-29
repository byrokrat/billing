<?php

declare(strict_types=1);

namespace byrokrat\billing;

/**
 * Basic implementation of AgentInterface
 */
class Agent implements AgentInterface
{
    use AttributesTrait;

    /**
     * @var string
     */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
