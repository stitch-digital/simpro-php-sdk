<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Clients;

final readonly class Client
{
    /**
     * @param  array<string, mixed>  $relationships
     */
    public function __construct(
        public string $id,
        public string $type,
        public ClientAttributes $attributes,
        public array $relationships = [],
    ) {}

    /**
     * Create from array response.
     */
    public static function fromArray(array $data): self
    {
        // Extract relationships from flattened structure
        $relationships = [];
        $relationshipFields = ['account', 'primary_contact', 'account_manager', 'billingcard', 'clientgroup', 'pricetier'];

        foreach ($relationshipFields as $field) {
            if (isset($data[$field])) {
                $relationships[$field] = $data[$field];
            }
        }

        return new self(
            id: (string) $data['id'],
            type: 'Client',
            attributes: ClientAttributes::fromArray($data),
            relationships: $relationships
        );
    }
}
