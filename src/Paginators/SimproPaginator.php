<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Paginators;

use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\PagedPaginator;

final class SimproPaginator extends PagedPaginator
{
    protected ?int $perPageLimit = 30;

    public function getTotalResults(): int
    {
        return (int) ($this->currentResponse?->header('Result-Total') ?? 0);
    }

    protected function isLastPage(Response $response): bool
    {
        $currentPage = $this->currentPage;

        $totalPages = $this->getTotalPages($response);

        return $currentPage > $totalPages;
    }

    protected function getPageItems(Response $response, Request $request): array
    {
        return $response->dto();
    }

    protected function getTotalPages(Response $response): int
    {
        return (int) ($response->header('Result-Pages') ?? 0);
    }

    protected function applyPagination(Request $request): Request
    {
        $request->query()->add('page', $this->currentPage);

        if ($this->perPageLimit !== null) {
            $request->query()->add('pageSize', $this->perPageLimit);
        }

        return $request;
    }
}
