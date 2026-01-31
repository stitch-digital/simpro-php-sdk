<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\ChartOfAccount;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\ChartOfAccounts\CreateChartOfAccountRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\ChartOfAccounts\DeleteChartOfAccountRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\ChartOfAccounts\GetChartOfAccountRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\ChartOfAccounts\ListChartOfAccountsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\ChartOfAccounts\UpdateChartOfAccountRequest;

/**
 * Resource for managing chart of accounts.
 *
 * @property AbstractSimproConnector $connector
 */
final class ChartOfAccountResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all chart of accounts.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListChartOfAccountsRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get detailed information for a specific chart of account.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $accountId, ?array $columns = null): ChartOfAccount
    {
        $request = new GetChartOfAccountRequest($this->companyId, $accountId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new chart of account.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $request = new CreateChartOfAccountRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update a chart of account.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $accountId, array $data): Response
    {
        $request = new UpdateChartOfAccountRequest($this->companyId, $accountId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a chart of account.
     */
    public function delete(int|string $accountId): Response
    {
        $request = new DeleteChartOfAccountRequest($this->companyId, $accountId);

        return $this->connector->send($request);
    }
}
