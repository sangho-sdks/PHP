<?php
declare(strict_types=1);

namespace Sangho\Exception;

class SanghoRateLimitException extends SanghoException
{
    public function __construct(public readonly int $retryAfter = 60)
    {
        parent::__construct(
            "Rate limit exceeded. Retry after {$retryAfter}s.",
            'rate_limit_exceeded',
            429
        );
    }
}
