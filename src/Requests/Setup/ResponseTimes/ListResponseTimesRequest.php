<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\ResponseTimes;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\ResponseTimeListItem;

final class ListResponseTimesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/responseTimes/";
    }

    /**
     * @return array<int, ResponseTimeListItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        /** @var array<int, array<string, mixed>> $data */
        $data = $response->json();

        return array_map(
            fn (array $item): ResponseTimeListItem => ResponseTimeListItem::fromArray($item),
            $data
        );
    }
}
