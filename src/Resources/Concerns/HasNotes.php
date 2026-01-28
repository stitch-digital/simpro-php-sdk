<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Concerns;

use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Note;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;

/**
 * Trait for resources that support notes.
 *
 * Add this trait to any resource that has a /notes/ sub-endpoint.
 */
trait HasNotes
{
    /**
     * Get the base path for this resource (e.g., "/api/v1.0/companies/0/jobs/1").
     */
    abstract protected function getBasePath(): string;

    /**
     * List all notes for this resource.
     */
    public function listNotes(): QueryBuilder
    {
        $request = new class($this->getBasePath()) extends \Saloon\Http\Request implements \Saloon\PaginationPlugin\Contracts\Paginatable
        {
            protected \Saloon\Enums\Method $method = \Saloon\Enums\Method::GET;

            public function __construct(private readonly string $basePath) {}

            public function resolveEndpoint(): string
            {
                return "{$this->basePath}/notes/";
            }

            /** @return array<Note> */
            public function createDtoFromResponse(\Saloon\Http\Response $response): array
            {
                return array_map(
                    fn (array $item) => Note::fromArray($item),
                    $response->json()
                );
            }
        };

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific note by ID.
     */
    public function getNote(int|string $noteId): Note
    {
        $basePath = $this->getBasePath();

        $request = new class($basePath, $noteId) extends \Saloon\Http\Request
        {
            protected \Saloon\Enums\Method $method = \Saloon\Enums\Method::GET;

            public function __construct(
                private readonly string $basePath,
                private readonly int|string $noteId,
            ) {}

            public function resolveEndpoint(): string
            {
                return "{$this->basePath}/notes/{$this->noteId}";
            }

            public function createDtoFromResponse(\Saloon\Http\Response $response): Note
            {
                return Note::fromArray($response->json());
            }
        };

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new note.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created note
     */
    public function createNote(array $data): int
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
                return "{$this->basePath}/notes/";
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
     * Update a note.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateNote(int|string $noteId, array $data): Response
    {
        $basePath = $this->getBasePath();

        $request = new class($basePath, $noteId, $data) extends \Saloon\Http\Request implements \Saloon\Contracts\Body\HasBody
        {
            use \Saloon\Traits\Body\HasJsonBody;

            protected \Saloon\Enums\Method $method = \Saloon\Enums\Method::PATCH;

            public function __construct(
                private readonly string $basePath,
                private readonly int|string $noteId,
                private readonly array $data,
            ) {}

            public function resolveEndpoint(): string
            {
                return "{$this->basePath}/notes/{$this->noteId}";
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
     * Delete a note.
     */
    public function deleteNote(int|string $noteId): Response
    {
        $basePath = $this->getBasePath();

        $request = new class($basePath, $noteId) extends \Saloon\Http\Request
        {
            protected \Saloon\Enums\Method $method = \Saloon\Enums\Method::DELETE;

            public function __construct(
                private readonly string $basePath,
                private readonly int|string $noteId,
            ) {}

            public function resolveEndpoint(): string
            {
                return "{$this->basePath}/notes/{$this->noteId}";
            }
        };

        return $this->connector->send($request);
    }
}
