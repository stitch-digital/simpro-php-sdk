<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\CustomField;

/**
 * Abstract base class for list detailed custom fields requests.
 */
abstract class AbstractListDetailedCustomFieldsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    /**
     * All columns available for custom fields.
     */
    private const DETAILED_COLUMNS = [
        'ID',
        'Name',
        'Type',
        'ListItems',
        'IsMandatory',
        'Order',
        'Archived',
        'Locked',
    ];

    public function __construct(
        protected readonly int $companyId,
    ) {}

    abstract protected function getResourcePath(): string;

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/customFields/{$this->getResourcePath()}/";
    }

    /**
     * @return array<string, string>
     */
    protected function defaultQuery(): array
    {
        return [
            'columns' => implode(',', self::DETAILED_COLUMNS),
        ];
    }

    /**
     * @return array<CustomField>
     */
    public function createDtoFromResponse(Response $response): array
    {
        /** @var array<int, array<string, mixed>> $data */
        $data = $response->json();

        return array_map(
            fn (array $item): CustomField => CustomField::fromArray($item),
            $data
        );
    }
}
