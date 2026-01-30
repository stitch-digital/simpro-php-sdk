<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Employees\Employee;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Employees\CreateEmployeeRequest;
use Simpro\PhpSdk\Simpro\Requests\Employees\DeleteEmployeeRequest;
use Simpro\PhpSdk\Simpro\Requests\Employees\GetEmployeeRequest;
use Simpro\PhpSdk\Simpro\Requests\Employees\ListEmployeesRequest;
use Simpro\PhpSdk\Simpro\Requests\Employees\UpdateEmployeeRequest;
use Simpro\PhpSdk\Simpro\Scopes\Employees\EmployeeScope;

/**
 * @property AbstractSimproConnector $connector
 */
final class EmployeeResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all employees.
     *
     * Returns a QueryBuilder that supports fluent search, ordering, and filtering.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListEmployeesRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get detailed information for a specific employee.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $employeeId, ?array $columns = null): Employee
    {
        $request = new GetEmployeeRequest($this->companyId, $employeeId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new employee.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created employee
     */
    public function create(array $data): int
    {
        $request = new CreateEmployeeRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing employee.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $employeeId, array $data): Response
    {
        $request = new UpdateEmployeeRequest($this->companyId, $employeeId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an employee.
     */
    public function delete(int|string $employeeId): Response
    {
        $request = new DeleteEmployeeRequest($this->companyId, $employeeId);

        return $this->connector->send($request);
    }

    /**
     * Navigate to a specific employee scope for nested resources.
     *
     * @example
     * // Access employee timesheets
     * $connector->employees(companyId: 0)->employee(employeeId: 123)->timesheets()->list();
     *
     * // Access employee custom fields
     * $connector->employees(companyId: 0)->employee(employeeId: 123)->customFields()->list();
     */
    public function employee(int|string $employeeId): EmployeeScope
    {
        return new EmployeeScope($this->connector, $this->companyId, $employeeId);
    }
}
