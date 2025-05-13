<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\HttpFoundation\Request;

readonly class PaginatedListRequest
{
    public function __construct(
        public int $page = 1,
        public int $limit = 20
    ) {}

    public static function ofRequest(Request $request): self
    {
        return new self(
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20)
        );
    }
}
