<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\VendorOrders;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\VendorOrderStatusCode;

/**
 * List all vendor order status codes with full details.
 */
final class ListDetailedVendorOrderStatusCodesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    /**
     * All columns available for vendor order status codes.
     */
    private const DETAILED_COLUMNS = [
        'ID',
        'Name',
        'Color',
        'Priority',
    ];

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/statusCodes/vendorOrders/";
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
     * @return array<VendorOrderStatusCode>
     */
    public function createDtoFromResponse(Response $response): array
    {
        /** @var array<int, array<string, mixed>> $data */
        $data = $response->json();

        return array_map(
            fn (array $item): VendorOrderStatusCode => VendorOrderStatusCode::fromArray($item),
            $data
        );
    }
}
