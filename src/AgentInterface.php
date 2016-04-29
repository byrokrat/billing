<?php

declare(strict_types=1);

namespace byrokrat\billing;

/**
 * Basic agent interface
 */
interface AgentInterface
{
    /**
     * Get name of agent
     */
    public function getName(): string;

    /**
     * Get attribute definied by key, returning default if attribute is not set
     *
     * @return mixed
     */
    public function getAttribute(string $key, $default = '');
}
