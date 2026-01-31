<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\VendorOrders;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteVendorOrderStatusCodeRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $statusCodeId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/statusCodes/vendorOrders/{$this->statusCodeId}";
    }
}
