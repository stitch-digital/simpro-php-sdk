<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Contractors\Contractor;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Contractors\CreateContractorRequest;
use Simpro\PhpSdk\Simpro\Requests\Contractors\DeleteContractorRequest;
use Simpro\PhpSdk\Simpro\Requests\Contractors\GetContractorRequest;
use Simpro\PhpSdk\Simpro\Requests\Contractors\ListContractorsDetailedRequest;
use Simpro\PhpSdk\Simpro\Requests\Contractors\ListContractorsRequest;
use Simpro\PhpSdk\Simpro\Requests\Contractors\UpdateContractorRequest;
use Simpro\PhpSdk\Simpro\Scopes\Contractors\ContractorScope;

/**
 * @property AbstractSimproConnector $connector
 */
final class ContractorResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all contractors.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListContractorsRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all contractors with all available columns.
     *
     * Returns Contractor DTOs with full nested data including Address, PrimaryContact,
     * EmergencyContact, AccountSetup, UserProfile, AssignedCostCenters, Zones,
     * CustomFields, Banking, Rates, and timestamps.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListContractorsDetailedRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get detailed information for a specific contractor.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $contractorId, ?array $columns = null): Contractor
    {
        $request = new GetContractorRequest($this->companyId, $contractorId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new contractor.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created contractor
     */
    public function create(array $data): int
    {
        $request = new CreateContractorRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing contractor.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $contractorId, array $data): Response
    {
        $request = new UpdateContractorRequest($this->companyId, $contractorId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a contractor.
     */
    public function delete(int|string $contractorId): Response
    {
        $request = new DeleteContractorRequest($this->companyId, $contractorId);

        return $this->connector->send($request);
    }

    /**
     * Navigate to a specific contractor scope for nested resources.
     *
     * @example
     * // Access contractor timesheets
     * $connector->contractors(companyId: 0)->contractor(contractorId: 123)->timesheets()->list();
     *
     * // Access contractor custom fields
     * $connector->contractors(companyId: 0)->contractor(contractorId: 123)->customFields()->list();
     */
    public function contractor(int|string $contractorId): ContractorScope
    {
        return new ContractorScope($this->connector, $this->companyId, $contractorId);
    }
}
