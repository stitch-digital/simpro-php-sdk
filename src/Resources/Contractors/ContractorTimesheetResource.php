<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Contractors;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Contractors\Timesheets\ListTimesheetsRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class ContractorTimesheetResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $contractorId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all timesheets for this contractor.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListTimesheetsRequest($this->companyId, $this->contractorId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }
}
