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
     * @param  array<Reference>|null  $companies
     */
    public function __construct(
        public int $id,
        public ?string $username = null,
        public ?string $email = null,
        public ?string $givenName = null,
        public ?string $familyName = null,
        public ?string $displayName = null,
        public ?array $companies = null,
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
            username: $data['Username'] ?? null,
            email: $data['Email'] ?? null,
            givenName: $data['GivenName'] ?? null,
            familyName: $data['FamilyName'] ?? null,
            displayName: $data['DisplayName'] ?? $data['Name'] ?? null,
            companies: isset($data['Companies']) ? array_map(
                fn (array $item) => Reference::fromArray($item),
                $data['Companies']
            ) : null,
        );
    }

    /**
     * Get the full name of the user.
     */
    public function fullName(): string
    {
        if ($this->displayName !== null) {
            return $this->displayName;
        }

        $parts = array_filter([$this->givenName, $this->familyName]);

        return implode(' ', $parts) ?: $this->username ?? '';
    }
}
