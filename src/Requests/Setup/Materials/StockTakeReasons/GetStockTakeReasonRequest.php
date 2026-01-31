<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Materials\StockTakeReasons;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\StockTakeReason;

/**
 * Get a specific StockTakeReason.
 */
final class GetStockTakeReasonRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $optionId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/materials/stockTakeReasons/{$this->optionId}";
    }

    public function createDtoFromResponse(Response $response): StockTakeReason
    {
        return StockTakeReason::fromResponse($response);
    }
}
