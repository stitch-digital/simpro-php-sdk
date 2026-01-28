<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Employees;

use DateTimeImmutable;
use Saloon\Http\Response;

final readonly class Employee
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $givenName,
        public ?string $familyName,
        public ?string $email,
        public ?string $phone,
        public ?string $mobile,
        public ?EmployeeAddress $address,
        public ?string $employeeNo,
        public ?DateTimeImmutable $dateOfBirth,
        public ?DateTimeImmutable $startDate,
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
            name: $data['Name'] ?? '',
            givenName: $data['GivenName'] ?? null,
            familyName: $data['FamilyName'] ?? null,
            email: $data['Email'] ?? null,
            phone: $data['Phone'] ?? null,
            mobile: $data['Mobile'] ?? null,
            address: isset($data['Address']) ? EmployeeAddress::fromArray($data['Address']) : null,
            employeeNo: $data['EmployeeNo'] ?? null,
            dateOfBirth: isset($data['DateOfBirth']) ? new DateTimeImmutable($data['DateOfBirth']) : null,
            startDate: isset($data['StartDate']) ? new DateTimeImmutable($data['StartDate']) : null,
            isArchived: $data['IsArchived'] ?? null,
            dateModified: isset($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
        );
    }
}
