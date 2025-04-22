<?php

declare(strict_types=1);

namespace App\Service\Workshop\Exception;

class DuplicateEmailException extends \RuntimeException
{
    public function __construct(string $email, int $code = 0, ?\Throwable $previous = null)
    {
        $message = sprintf('Użytkownik o adresie e-mail "%s" już istnieje.', $email);
        parent::__construct($message, $code, $previous);
    }
} 