<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Employees;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Employees\Licences\Licence;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
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
        private readonly int|string $companyId,
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
}
