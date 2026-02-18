<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Quotes\CostCenters;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\Assets\Asset;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\Assets\CreateQuoteCostCenterAssetRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\Assets\GetQuoteCostCenterAssetRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\Assets\ListQuoteCostCenterAssetsRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\Assets\UpdateQuoteCostCenterAssetRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class QuoteCostCenterAssetResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $quoteId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
    ) {
        parent::__construct($connector);
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListQuoteCostCenterAssetsRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    public function get(int|string $assetId): Asset
    {
        $request = new GetQuoteCostCenterAssetRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $assetId);

        return $this->connector->send($request)->dto();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $request = new CreateQuoteCostCenterAssetRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $assetId, array $data): Response
    {
        $request = new UpdateQuoteCostCenterAssetRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $assetId, $data);

        return $this->connector->send($request);
    }
}
