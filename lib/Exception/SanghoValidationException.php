<?php
declare(strict_types=1);

namespace Sangho\Exception;

class SanghoValidationException extends SanghoException
{
    public function getFieldErrors(): array
    {
        $detail = $this->raw['detail'] ?? $this->raw['errors'] ?? [];
        return is_array($detail) ? $detail : [];
    }
}
