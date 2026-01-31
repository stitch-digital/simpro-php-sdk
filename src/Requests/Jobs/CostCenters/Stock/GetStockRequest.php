<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Stock;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\Stock\StockItem;

final class GetStockRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $jobId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
        private readonly int|string $stockId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/jobs/{$this->jobId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/stock/{$this->stockId}";
    }

    public function createDtoFromResponse(Response $response): StockItem
    {
        return StockItem::fromResponse($response);
    }
}
