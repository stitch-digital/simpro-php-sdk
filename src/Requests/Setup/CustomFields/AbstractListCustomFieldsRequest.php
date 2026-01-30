<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\CustomFieldListItem;

/**
 * Abstract base class for list custom fields requests.
 */
abstract class AbstractListCustomFieldsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int|string $companyId,
    ) {}

    abstract protected function getResourcePath(): string;

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/customFields/{$this->getResourcePath()}/";
    }

    /**
     * @return array<CustomFieldListItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => CustomFieldListItem::fromArray($item),
            $data
        );
    }
}
