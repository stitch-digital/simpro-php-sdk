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
        $dto = $response->dto();

        // If the DTO is already an array, return it
        if (is_array($dto)) {
            return $dto;
        }

        // If the DTO is an object with an array property containing items,
        // extract that property. This handles list response DTOs like:
        // - ClientListResponse with ->clients
        // - JobListResponse with ->jobs
        // The specific response DTO should have a property matching the resource name
        if (is_object($dto)) {
            // Get all public properties
            $properties = get_object_vars($dto);

            // If there's only one property and it's an array, use it
            if (count($properties) === 1) {
                $value = reset($properties);
                if (is_array($value)) {
                    return $value;
                }
            }
        }

        // Fallback: wrap single item in array
        return [$dto];
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
