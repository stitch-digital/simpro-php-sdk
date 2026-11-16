<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoiceCostCenters\ListRecurringInvoiceCostCentersRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class RecurringInvoiceCostCenterResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List cost centers across all recurring invoices.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     *
     * @example
     * $lines = $connector->recurringInvoiceCostCenters(companyId: 0)->list(['RecurringInvoice.ID' => 501])->all();
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListRecurringInvoiceCostCentersRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }
}
