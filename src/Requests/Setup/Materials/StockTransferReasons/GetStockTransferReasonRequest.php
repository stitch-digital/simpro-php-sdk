<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Materials\StockTransferReasons;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\StockTransferReason;

/**
 * Get a specific StockTransferReason.
 */
final class GetStockTransferReasonRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $optionId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/materials/stockTransferReasons/{$this->optionId}";
    }

    public function createDtoFromResponse(Response $response): StockTransferReason
    {
        return StockTransferReason::fromResponse($response);
    }
}
