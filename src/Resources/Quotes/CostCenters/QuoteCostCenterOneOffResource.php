<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Quotes\CostCenters;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\OneOffs\OneOffItem;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\OneOffs\CreateQuoteCostCenterOneOffRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\OneOffs\DeleteQuoteCostCenterOneOffRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\OneOffs\GetQuoteCostCenterOneOffRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\OneOffs\ListQuoteCostCenterOneOffsRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\OneOffs\UpdateQuoteCostCenterOneOffRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class QuoteCostCenterOneOffResource extends BaseResource
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
        $request = new ListQuoteCostCenterOneOffsRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    public function get(int|string $oneOffId): OneOffItem
    {
        $request = new GetQuoteCostCenterOneOffRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $oneOffId);

        return $this->connector->send($request)->dto();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $request = new CreateQuoteCostCenterOneOffRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $oneOffId, array $data): Response
    {
        $request = new UpdateQuoteCostCenterOneOffRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $oneOffId, $data);

        return $this->connector->send($request);
    }

    public function delete(int|string $oneOffId): Response
    {
        $request = new DeleteQuoteCostCenterOneOffRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $oneOffId);

        return $this->connector->send($request);
    }
}
