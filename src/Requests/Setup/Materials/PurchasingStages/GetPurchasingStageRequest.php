<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Materials\PurchasingStages;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\PurchasingStage;

/**
 * Get a specific PurchasingStage.
 */
final class GetPurchasingStageRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $purchasingStageId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/materials/purchasingStages/{$this->purchasingStageId}";
    }

    public function createDtoFromResponse(Response $response): PurchasingStage
    {
        return PurchasingStage::fromResponse($response);
    }
}
