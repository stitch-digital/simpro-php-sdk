<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\Labor;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\ServiceFee;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\Labor\ServiceFees\GetServiceFeeRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Labor\ServiceFees\ListServiceFeesRequest;

/**
 * Resource for managing ServiceFees.
 *
 * @property AbstractSimproConnector $connector
 */
final class ServiceFeeResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListServiceFeesRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific item.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $serviceFeeId, ?array $columns = null): ServiceFee
    {
        $request = new GetServiceFeeRequest($this->companyId, $serviceFeeId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }
}
