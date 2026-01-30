<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\VendorOrders;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\VendorOrderStatusCode;

final class GetVendorOrderStatusCodeRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $statusCodeId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/statusCodes/vendorOrders/{$this->statusCodeId}";
    }

    public function createDtoFromResponse(Response $response): VendorOrderStatusCode
    {
        return VendorOrderStatusCode::fromResponse($response);
    }
}
