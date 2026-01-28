<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Concerns;

use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Attachment;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;

/**
 * Trait for resources that support file attachments.
 *
 * Add this trait to any resource that has /attachments/files/ and /attachments/folders/ sub-endpoints.
 */
trait HasAttachments
{
    /**
     * Get the base path for this resource (e.g., "/api/v1.0/companies/0/jobs/1").
     */
    abstract protected function getBasePath(): string;

    /**
     * List all attachment files for this resource.
     */
    public function listAttachments(): QueryBuilder
    {
        $request = new class($this->getBasePath()) extends \Saloon\Http\Request implements \Saloon\PaginationPlugin\Contracts\Paginatable
        {
            protected \Saloon\Enums\Method $method = \Saloon\Enums\Method::GET;

            public function __construct(private readonly string $basePath) {}

            public function resolveEndpoint(): string
            {
                return "{$this->basePath}/attachments/files/";
            }

            /** @return array<Attachment> */
            public function createDtoFromResponse(\Saloon\Http\Response $response): array
            {
                return array_map(
                    fn (array $item) => Attachment::fromArray($item),
                    $response->json()
                );
            }
        };

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific attachment file by ID.
     */
    public function getAttachment(int|string $fileId): Attachment
    {
        $basePath = $this->getBasePath();

        $request = new class($basePath, $fileId) extends \Saloon\Http\Request
        {
            protected \Saloon\Enums\Method $method = \Saloon\Enums\Method::GET;

            public function __construct(
                private readonly string $basePath,
                private readonly int|string $fileId,
            ) {}

            public function resolveEndpoint(): string
            {
                return "{$this->basePath}/attachments/files/{$this->fileId}";
            }

            public function createDtoFromResponse(\Saloon\Http\Response $response): Attachment
            {
                return Attachment::fromArray($response->json());
            }
        };

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new attachment.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created attachment
     */
    public function createAttachment(array $data): int
    {
        $basePath = $this->getBasePath();

        $request = new class($basePath, $data) extends \Saloon\Http\Request implements \Saloon\Contracts\Body\HasBody
        {
            use \Saloon\Traits\Body\HasJsonBody;

            protected \Saloon\Enums\Method $method = \Saloon\Enums\Method::POST;

            public function __construct(
                private readonly string $basePath,
                private readonly array $data,
            ) {}

            public function resolveEndpoint(): string
            {
                return "{$this->basePath}/attachments/files/";
            }

            /** @return array<string, mixed> */
            protected function defaultBody(): array
            {
                return $this->data;
            }

            public function createDtoFromResponse(\Saloon\Http\Response $response): int
            {
                return (int) $response->json()['ID'];
            }
        };

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an attachment.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateAttachment(int|string $fileId, array $data): Response
    {
        $basePath = $this->getBasePath();

        $request = new class($basePath, $fileId, $data) extends \Saloon\Http\Request implements \Saloon\Contracts\Body\HasBody
        {
            use \Saloon\Traits\Body\HasJsonBody;

            protected \Saloon\Enums\Method $method = \Saloon\Enums\Method::PATCH;

            public function __construct(
                private readonly string $basePath,
                private readonly int|string $fileId,
                private readonly array $data,
            ) {}

            public function resolveEndpoint(): string
            {
                return "{$this->basePath}/attachments/files/{$this->fileId}";
            }

            /** @return array<string, mixed> */
            protected function defaultBody(): array
            {
                return $this->data;
            }
        };

        return $this->connector->send($request);
    }

    /**
     * Delete an attachment.
     */
    public function deleteAttachment(int|string $fileId): Response
    {
        $basePath = $this->getBasePath();

        $request = new class($basePath, $fileId) extends \Saloon\Http\Request
        {
            protected \Saloon\Enums\Method $method = \Saloon\Enums\Method::DELETE;

            public function __construct(
                private readonly string $basePath,
                private readonly int|string $fileId,
            ) {}

            public function resolveEndpoint(): string
            {
                return "{$this->basePath}/attachments/files/{$this->fileId}";
            }
        };

        return $this->connector->send($request);
    }

    /**
     * List all attachment folders for this resource.
     */
    public function listAttachmentFolders(): QueryBuilder
    {
        $request = new class($this->getBasePath()) extends \Saloon\Http\Request implements \Saloon\PaginationPlugin\Contracts\Paginatable
        {
            protected \Saloon\Enums\Method $method = \Saloon\Enums\Method::GET;

            public function __construct(private readonly string $basePath) {}

            public function resolveEndpoint(): string
            {
                return "{$this->basePath}/attachments/folders/";
            }

            /** @return array<Reference> */
            public function createDtoFromResponse(\Saloon\Http\Response $response): array
            {
                return array_map(
                    fn (array $item) => Reference::fromArray($item),
                    $response->json()
                );
            }
        };

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Create a new attachment folder.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created folder
     */
    public function createAttachmentFolder(array $data): int
    {
        $basePath = $this->getBasePath();

        $request = new class($basePath, $data) extends \Saloon\Http\Request implements \Saloon\Contracts\Body\HasBody
        {
            use \Saloon\Traits\Body\HasJsonBody;

            protected \Saloon\Enums\Method $method = \Saloon\Enums\Method::POST;

            public function __construct(
                private readonly string $basePath,
                private readonly array $data,
            ) {}

            public function resolveEndpoint(): string
            {
                return "{$this->basePath}/attachments/folders/";
            }

            /** @return array<string, mixed> */
            protected function defaultBody(): array
            {
                return $this->data;
            }

            public function createDtoFromResponse(\Saloon\Http\Response $response): int
            {
                return (int) $response->json()['ID'];
            }
        };

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete an attachment folder.
     */
    public function deleteAttachmentFolder(int|string $folderId): Response
    {
        $basePath = $this->getBasePath();

        $request = new class($basePath, $folderId) extends \Saloon\Http\Request
        {
            protected \Saloon\Enums\Method $method = \Saloon\Enums\Method::DELETE;

            public function __construct(
                private readonly string $basePath,
                private readonly int|string $folderId,
            ) {}

            public function resolveEndpoint(): string
            {
                return "{$this->basePath}/attachments/folders/{$this->folderId}";
            }
        };

        return $this->connector->send($request);
    }
}
