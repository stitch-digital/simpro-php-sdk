<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Labor\FitTimes;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\FitTime;

/**
 * List all fit times with full details.
 */
final class ListDetailedFitTimesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    /**
     * All columns available for fit times.
     */
    private const DETAILED_COLUMNS = [
        'ID',
        'Name',
        'Multiplier',
        'DisplayOrder',
        'Archived',
    ];

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/labor/fitTimes/";
    }

    /**
     * @return array<string, string>
     */
    protected function defaultQuery(): array
    {
        return [
            'columns' => implode(',', self::DETAILED_COLUMNS),
        ];
    }

    /**
     * @return array<FitTime>
     */
    public function createDtoFromResponse(Response $response): array
    {
        /** @var array<int, array<string, mixed>> $data */
        $data = $response->json();

        return array_map(
            fn (array $item): FitTime => FitTime::fromArray($item),
            $data
        );
    }
}
