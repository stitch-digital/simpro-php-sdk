<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\ResponseTimes;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\ResponseTime;

/**
 * List response times with full details.
 *
 * Uses the columns parameter to request all available fields,
 * returning full ResponseTime DTOs instead of list items.
 */
final class ListDetailedResponseTimesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    private const DETAILED_COLUMNS = [
        'ID',
        'Name',
        'Days',
        'Hours',
        'Minutes',
        'IncludeWeekends',
        'Archived',
    ];

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/responseTimes/";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return [
            'columns' => implode(',', self::DETAILED_COLUMNS),
        ];
    }

    /**
     * @return array<int, ResponseTime>
     */
    public function createDtoFromResponse(Response $response): array
    {
        /** @var array<int, array<string, mixed>> $data */
        $data = $response->json();

        return array_map(
            fn (array $item): ResponseTime => ResponseTime::fromArray($item),
            $data
        );
    }
}
