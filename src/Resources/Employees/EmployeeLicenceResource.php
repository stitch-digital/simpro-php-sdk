<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Employees;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Employees\Licences\Licence;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\Employees\Licences\CreateEmployeeLicenceRequest;
use Simpro\PhpSdk\Simpro\Requests\Employees\Licences\DeleteEmployeeLicenceRequest;
use Simpro\PhpSdk\Simpro\Requests\Employees\Licences\GetEmployeeLicenceRequest;
use Simpro\PhpSdk\Simpro\Requests\Employees\Licences\ListEmployeeLicencesRequest;
use Simpro\PhpSdk\Simpro\Requests\Employees\Licences\UpdateEmployeeLicenceRequest;

/**
 * Resource for managing employee licences.
 *
 * @property AbstractSimproConnector $connector
 */
final class EmployeeLicenceResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $employeeId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all licences for this employee.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListEmployeeLicencesRequest($this->companyId, $this->employeeId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific licence.
     */
    public function get(int|string $licenceId): Licence
    {
        $request = new GetEmployeeLicenceRequest($this->companyId, $this->employeeId, $licenceId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new licence.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created licence
     */
    public function create(array $data): int
    {
        $request = new CreateEmployeeLicenceRequest($this->companyId, $this->employeeId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing licence.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $licenceId, array $data): Response
    {
        $request = new UpdateEmployeeLicenceRequest($this->companyId, $this->employeeId, $licenceId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a licence.
     */
    public function delete(int|string $licenceId): Response
    {
        $request = new DeleteEmployeeLicenceRequest($this->companyId, $this->employeeId, $licenceId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple employee licences in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/employees/{$this->employeeId}/licences",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple employee licences in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/employees/{$this->employeeId}/licences",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple employee licences in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/employees/{$this->employeeId}/licences",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
