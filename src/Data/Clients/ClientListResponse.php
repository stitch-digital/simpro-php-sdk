<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Clients;

use Simpro\PhpSdk\Simpro\Data\PaginationLinks;
use Simpro\PhpSdk\Simpro\Data\PaginationMeta;

final readonly class ClientListResponse
{
    /**
     * @param  array<int, Client>  $clients
     */
    public function __construct(
        public array $clients,
        public ?PaginationLinks $links = null,
        public ?PaginationMeta $meta = null,
    ) {}

    /**
     * Create from array response.
     */
    public static function fromArray(array $data): self
    {
        $clients = array_map(
            Client::fromArray(...),
            $data['results'] ?? []
        );

        $links = isset($data['links']) ? PaginationLinks::fromArray($data['links']) : null;
        $meta = isset($data['meta']) ? PaginationMeta::fromArray($data['meta']) : null;

        return new self($clients, $links, $meta);
    }
}
