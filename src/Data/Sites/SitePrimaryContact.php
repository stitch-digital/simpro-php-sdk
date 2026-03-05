<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Sites;

final readonly class SitePrimaryContact
{
    public function __construct(
        public ?SiteContactReference $contact = null,
        public ?string $title = null,
        public ?string $givenName = null,
        public ?string $familyName = null,
        public ?string $email = null,
        public ?string $workPhone = null,
        public ?string $cellPhone = null,
        public ?string $fax = null,
        public ?string $position = null,
        public ?string $preferredNotificationMethod = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            contact: isset($data['Contact']) ? SiteContactReference::fromArray($data['Contact']) : null,
            title: $data['Title'] ?? null,
            givenName: $data['GivenName'] ?? null,
            familyName: $data['FamilyName'] ?? null,
            email: $data['Email'] ?? null,
            workPhone: $data['WorkPhone'] ?? null,
            cellPhone: $data['CellPhone'] ?? null,
            fax: $data['Fax'] ?? null,
            position: $data['Position'] ?? null,
            preferredNotificationMethod: $data['PreferredNotificationMethod'] ?? null,
        );
    }
}
