<?php

declare(strict_types=1);

namespace App\Service\Workshop\Exception;

class DuplicateNipException extends \RuntimeException
{
    public function __construct(string $nip, int $code = 0, ?\Throwable $previous = null)
    {
        $message = sprintf('Firma o numerze NIP "%s" już istnieje.', $nip);
        parent::__construct($message, $code, $previous);
    }
} 