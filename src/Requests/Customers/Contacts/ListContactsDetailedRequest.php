<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Customers\Contacts;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Customers\Contacts\Contact;

/**
 * Request to list contacts with all available columns.
 *
 * Returns detailed Contact DTOs with full nested data structures.
 * Uses the columns parameter to request all available fields in a single request.
 */
final class ListContactsDetailedRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $customerId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/customers/{$this->customerId}/contacts/";
    }

    /**
     * @return array<string, string>
     */
    protected function defaultQuery(): array
    {
        return [
            'columns' => implode(',', [
                'ID',
                'Title',
                'GivenName',
                'FamilyName',
                'Email',
                'WorkPhone',
                'Fax',
                'CellPhone',
                'AltPhone',
                'Department',
                'Position',
                'Notes',
                'CustomFields',
                'DateModified',
                'QuoteContact',
                'JobContact',
                'InvoiceContact',
                'StatementContact',
                'PrimaryStatementContact',
                'PrimaryInvoiceContact',
                'PrimaryJobContact',
                'PrimaryQuoteContact',
            ]),
        ];
    }

    /**
     * @return array<Contact>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => Contact::fromArray($item),
            $data
        );
    }
}
