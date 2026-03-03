<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Employees;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Employees\Employee;

/**
 * Request to list all employees with all available columns.
 *
 * Returns detailed Employee DTOs with full nested data structures.
 * Uses the columns parameter to request all available fields in a single request.
 */
final class ListEmployeesDetailedRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/employees/";
    }

    /**
     * @return array<string, string>
     */
    protected function defaultQuery(): array
    {
        return [
            'columns' => implode(',', [
                'ID',
                'Name',
                'Position',
                'Availability',
                'Address',
                'DateOfHire',
                'DateOfBirth',
                'PrimaryContact',
                'EmergencyContact',
                'AccountSetup',
                'UserProfile',
                'DateCreated',
                'DateModified',
                'Archived',
                'AssignedCostCenters',
                'Zones',
                'DefaultZone',
                'DefaultCompany',
                'CustomFields',
                'MaskedSSN',
                'Banking',
                'PayRates',
            ]),
        ];
    }

    /**
     * @return array<Employee>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => Employee::fromArray($item),
            $data
        );
    }
}
