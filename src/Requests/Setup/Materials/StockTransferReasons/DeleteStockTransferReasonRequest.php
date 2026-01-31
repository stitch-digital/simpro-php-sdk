<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Materials\StockTransferReasons;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a StockTransferReason.
 */
final class DeleteStockTransferReasonRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $optionId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/materials/stockTransferReasons/{$this->optionId}";
    }
}
