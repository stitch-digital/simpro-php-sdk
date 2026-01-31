<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\VendorOrders;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\VendorOrderStatusCodeListItem;

final class ListVendorOrderStatusCodesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/statusCodes/vendorOrders/";
    }

    /**
     * @return array<int, VendorOrderStatusCodeListItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        /** @var array<int, array<string, mixed>> $data */
        $data = $response->json();

        return array_map(
            fn (array $item): VendorOrderStatusCodeListItem => VendorOrderStatusCodeListItem::fromArray($item),
            $data
        );
    }
}
