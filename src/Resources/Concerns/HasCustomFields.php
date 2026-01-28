<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Concerns;

use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;

/**
 * Trait for resources that support custom fields.
 *
 * Add this trait to any resource that has a /customFields/ sub-endpoint.
 */
trait HasCustomFields
{
    /**
     * Get the base path for this resource (e.g., "/api/v1.0/companies/0/jobs/1").
     */
    abstract protected function getBasePath(): string;

    /**
     * List all custom fields for this resource.
     */
    public function listCustomFields(): QueryBuilder
    {
        $request = new class($this->getBasePath()) extends \Saloon\Http\Request implements \Saloon\PaginationPlugin\Contracts\Paginatable
        {
            protected \Saloon\Enums\Method $method = \Saloon\Enums\Method::GET;

            public function __construct(private readonly string $basePath) {}

            public function resolveEndpoint(): string
            {
                return "{$this->basePath}/customFields/";
            }

            /** @return array<CustomField> */
            public function createDtoFromResponse(\Saloon\Http\Response $response): array
            {
                return array_map(
                    fn (array $item) => CustomField::fromArray($item),
                    $response->json()
                );
            }
        };

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific custom field by ID.
     */
    public function getCustomField(int|string $customFieldId): CustomField
    {
        $basePath = $this->getBasePath();

        $request = new class($basePath, $customFieldId) extends \Saloon\Http\Request
        {
            protected \Saloon\Enums\Method $method = \Saloon\Enums\Method::GET;

            public function __construct(
                private readonly string $basePath,
                private readonly int|string $customFieldId,
            ) {}

            public function resolveEndpoint(): string
            {
                return "{$this->basePath}/customFields/{$this->customFieldId}";
            }

            public function createDtoFromResponse(\Saloon\Http\Response $response): CustomField
            {
                return CustomField::fromArray($response->json());
            }
        };

        return $this->connector->send($request)->dto();
    }

    /**
     * Update a custom field value.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateCustomField(int|string $customFieldId, array $data): Response
    {
        $basePath = $this->getBasePath();

        $request = new class($basePath, $customFieldId, $data) extends \Saloon\Http\Request implements \Saloon\Contracts\Body\HasBody
        {
            use \Saloon\Traits\Body\HasJsonBody;

            protected \Saloon\Enums\Method $method = \Saloon\Enums\Method::PATCH;

            public function __construct(
                private readonly string $basePath,
                private readonly int|string $customFieldId,
                private readonly array $data,
            ) {}

            public function resolveEndpoint(): string
            {
                return "{$this->basePath}/customFields/{$this->customFieldId}";
            }

            /** @return array<string, mixed> */
            protected function defaultBody(): array
            {
                return $this->data;
            }
        };

        return $this->connector->send($request);
    }
}
