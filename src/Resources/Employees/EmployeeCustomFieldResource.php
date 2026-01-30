<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Employees;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Employees\CustomFields\GetEmployeeCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Employees\CustomFields\ListEmployeeCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Employees\CustomFields\UpdateEmployeeCustomFieldRequest;

/**
 * Resource for managing employee custom fields.
 *
 * @property AbstractSimproConnector $connector
 */
final class EmployeeCustomFieldResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
        private readonly int|string $employeeId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all custom fields for this employee.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListEmployeeCustomFieldsRequest($this->companyId, $this->employeeId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific custom field.
     */
    public function get(int|string $customFieldId): CustomField
    {
        $request = new GetEmployeeCustomFieldRequest($this->companyId, $this->employeeId, $customFieldId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update a custom field value.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $customFieldId, array $data): Response
    {
        $request = new UpdateEmployeeCustomFieldRequest($this->companyId, $this->employeeId, $customFieldId, $data);

        return $this->connector->send($request);
    }
}
