<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Contractors;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Contractors\Contractor;

final class ListContractorsDetailedRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/contractors/";
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
                'Licences',
                'CustomFields',
                'EIN',
                'MaskedSSN',
                'CompanyNumber',
                'ContactName',
                'Currency',
                'Banking',
                'Rates',
                'DisplayOrder',
            ]),
        ];
    }

    /**
     * @return array<Contractor>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => Contractor::fromArray($item),
            $data
        );
    }
}
