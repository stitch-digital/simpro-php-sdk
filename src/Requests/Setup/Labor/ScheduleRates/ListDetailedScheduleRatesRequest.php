<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Labor\ScheduleRates;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\ScheduleRate;

/**
 * List all schedule rates with full details.
 */
final class ListDetailedScheduleRatesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    /**
     * All columns available for schedule rates.
     */
    private const DETAILED_COLUMNS = [
        'ID',
        'Name',
        'Multiplier',
        'ShowInMobile',
        'ShowInConnect',
        'IncOverhead',
        'ActivityOnly',
        'ScheduleColor',
        'DisplayOrder',
        'Archived',
        'HourlyAllowance',
        'PayRateOverride',
    ];

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/labor/scheduleRates/";
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
     * @return array<ScheduleRate>
     */
    public function createDtoFromResponse(Response $response): array
    {
        /** @var array<int, array<string, mixed>> $data */
        $data = $response->json();

        return array_map(
            fn (array $item): ScheduleRate => ScheduleRate::fromArray($item),
            $data
        );
    }
}
