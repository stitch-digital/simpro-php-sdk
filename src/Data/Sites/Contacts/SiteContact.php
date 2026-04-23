<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Sites\Contacts;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;

final readonly class SiteContact
{
    /**
     * @param  array<CustomField>|null  $customFields
     */
    public function __construct(
        public int $id,
        public ?string $title,
        public ?string $givenName,
        public ?string $familyName,
        public ?string $email,
        public ?string $workPhone,
        public ?string $fax,
        public ?string $cellPhone,
        public ?string $altPhone,
        public ?string $department,
        public ?string $position,
        public ?string $notes,
        public ?array $customFields,
        public ?DateTimeImmutable $dateModified,
        public ?bool $primaryContact,
    ) {}

    public static function fromResponse(Response $response): self
    {
        return self::fromArray($response->json());
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            title: $data['Title'] ?? null,
            givenName: $data['GivenName'] ?? null,
            familyName: $data['FamilyName'] ?? null,
            email: $data['Email'] ?? null,
            workPhone: $data['WorkPhone'] ?? null,
            fax: $data['Fax'] ?? null,
            cellPhone: $data['CellPhone'] ?? null,
            altPhone: $data['AltPhone'] ?? null,
            department: $data['Department'] ?? null,
            position: $data['Position'] ?? null,
            notes: $data['Notes'] ?? null,
            customFields: isset($data['CustomFields']) ? array_map(fn (array $item) => CustomField::fromArray($item), $data['CustomFields']) : null,
            dateModified: ! empty($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
            primaryContact: $data['PrimaryContact'] ?? null,
        );
    }
}
