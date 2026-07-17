<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Logs\ListJobLogsRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class JobLogResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all job logs for the company.
     *
     * @param  array<string, mixed>  $filters
     *
     * @example
     * // Status changes newer than a log id, oldest first
     * $connector->jobLogs(companyId: 0)->list()
     *     ->where('ID', '>', 3609009)
     *     ->where('Message', 'like', 'Updated Status%')
     *     ->orderByAsc('ID')
     *     ->collect();
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListJobLogsRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }
}
