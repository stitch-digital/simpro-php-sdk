<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers;

use DateTimeImmutable;
use Saloon\Http\Response;

final readonly class Customer
{
    public function __construct(
        public int $id,
        public string $companyName,
        public string $type,
        public ?string $givenName,
        public ?string $familyName,
        public ?string $email,
        public ?string $phone,
        public ?string $altPhone,
        public ?string $fax,
        public ?CustomerAddress $address,
        public ?string $abn,
        public ?string $website,
        public ?bool $isArchived,
        public ?DateTimeImmutable $dateModified,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            companyName: $data['CompanyName'] ?? '',
            type: $data['Type'] ?? '',
            givenName: $data['GivenName'] ?? null,
            familyName: $data['FamilyName'] ?? null,
            email: $data['Email'] ?? null,
            phone: $data['Phone'] ?? null,
            altPhone: $data['AltPhone'] ?? null,
            fax: $data['Fax'] ?? null,
            address: isset($data['Address']) ? CustomerAddress::fromArray($data['Address']) : null,
            abn: $data['ABN'] ?? null,
            website: $data['Website'] ?? null,
            isArchived: $data['IsArchived'] ?? null,
            dateModified: isset($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
        );
    }
}
