<?php
declare(strict_types=1);

namespace Sangho\Exception;

class SanghoException extends \RuntimeException
{
    public function __construct(
        string $message = '',
        public readonly string $errorCode = 'api_error',
        public readonly int $statusCode = 0,
        public readonly array $raw = [],
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }
}
