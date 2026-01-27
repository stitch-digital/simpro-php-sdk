<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Companies\Company;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Companies\GetCompanyRequest;
use Simpro\PhpSdk\Simpro\Requests\Companies\ListCompaniesDetailedRequest;
use Simpro\PhpSdk\Simpro\Requests\Companies\ListCompaniesRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class CompanyResource extends BaseResource
{
    /**
     * List all companies with basic information (ID and Name only).
     * This is a lightweight method for quick lookups and dropdowns.
     *
     * Returns a QueryBuilder that supports fluent search, ordering, and filtering.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     *
     * @example
     * // Simple list
     * $companies = $connector->companies()->list()->all();
     *
     * // With fluent search
     * $result = $connector->companies()->list()
     *     ->search(Search::make()->column('Name')->find('Test'))
     *     ->orderByDesc('Name')
     *     ->first();
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListCompaniesRequest;

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all companies with complete information (all fields).
     * Use this when you need detailed company data for multiple companies
     * without making individual get() calls (avoids N+1 query pattern).
     *
     * Returns a QueryBuilder that supports fluent search, ordering, and filtering.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     *
     * @example
     * // With fluent search
     * $result = $connector->companies()->listDetailed()
     *     ->search(Search::make()->column('Name')->find('Test'))
     *     ->collect()
     *     ->first();
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListCompaniesDetailedRequest;

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get detailed information for a specific company.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $companyId, ?array $columns = null): Company
    {
        $request = new GetCompanyRequest($companyId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Get the default company (ID = 0).
     * This is the primary company in single-company environments.
     */
    public function getDefault(): Company
    {
        return $this->get(0);
    }

    /**
     * Search companies by name (returns simple list with ID and Name only).
     */
    public function findByName(string $name): QueryBuilder
    {
        return $this->list(['Name' => $name]);
    }

    /**
     * Search companies by name (returns detailed list with all fields).
     */
    public function findByNameDetailed(string $name): QueryBuilder
    {
        return $this->listDetailed(['Name' => $name]);
    }
}
