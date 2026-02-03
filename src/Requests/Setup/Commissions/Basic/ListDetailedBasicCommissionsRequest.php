<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Commissions\Basic;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\BasicCommission;

/**
 * List all basic commissions with full details.
 */
final class ListDetailedBasicCommissionsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    /**
     * All columns available for basic commissions.
     */
    private const DETAILED_COLUMNS = [
        'ID',
        'Name',
        'Type',
        'DisplayOrder',
        'Rule',
        'Rate',
        'Trigger',
    ];

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/commissions/basic/";
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
     * @return array<BasicCommission>
     */
    public function createDtoFromResponse(Response $response): array
    {
        /** @var array<int, array<string, mixed>> $data */
        $data = $response->json();

        return array_map(
            fn (array $item): BasicCommission => BasicCommission::fromArray($item),
            $data
        );
    }
}
