<?php

namespace byrokrat\billing;

/**
 * Exception thrown if an error which can only be found on runtime occurs
 */
class RuntimeException extends \RuntimeException implements Exception
{
}
