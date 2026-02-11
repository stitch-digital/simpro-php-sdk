<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\CurrentUser;

use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * DTO for the current authenticated user.
 */
final readonly class CurrentUser
{
    /**
     * @param  array<Reference>|null  $accessibleCompanies
     */
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?string $type = null,
        public ?int $typeId = null,
        public ?string $preferredLanguage = null,
        public ?array $accessibleCompanies = null,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            name: $data['Name'] ?? null,
            type: $data['Type'] ?? null,
            typeId: isset($data['TypeID']) ? (int) $data['TypeID'] : null,
            preferredLanguage: $data['PreferredLanguage'] ?? null,
            accessibleCompanies: isset($data['AccessibleCompanies']) ? array_map(
                fn (array $item) => Reference::fromArray($item),
                $data['AccessibleCompanies']
            ) : null,
        );
    }
}
