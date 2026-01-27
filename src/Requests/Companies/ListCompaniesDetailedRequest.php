<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Companies;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Companies\Company;

final class ListCompaniesDetailedRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/api/v1.0/companies/';
    }

    protected function defaultQuery(): array
    {
        return [
            'columns' => implode(',', [
                'ID',
                'Name',
                'Phone',
                'Fax',
                'Email',
                'Address',
                'BillingAddress',
                'EIN',
                'CompanyNo',
                'Licence',
                'Website',
                'Banking',
                'CISCertNo',
                'EmployerTaxRefNo',
                'Timezone',
                'TimezoneOffset',
                'DefaultLanguage',
                'Template',
                'MultiCompanyLabel',
                'MultiCompanyColor',
                'Currency',
                'Country',
                'TaxName',
                'UIDateFormat',
                'UITimeFormat',
                'ScheduleFormat',
                'SingleCostCenterMode',
                'DateModified',
                'DefaultCostCenter',
            ]),
        ];
    }

    /**
     * @return array<Company>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => Company::fromArray($item),
            $data
        );
    }
}
