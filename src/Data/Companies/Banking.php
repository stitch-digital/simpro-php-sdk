<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Companies;

final readonly class Banking
{
    public function __construct(
        public string $bank,
        public string $branchCode,
        public string $accountName,
        public string $routingNo,
        public string $accountNo,
        public string $iban,
        public string $swiftCode,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            bank: $data['Bank'] ?? '',
            branchCode: $data['BranchCode'] ?? '',
            accountName: $data['AccountName'] ?? '',
            routingNo: $data['RoutingNo'] ?? '',
            accountNo: $data['AccountNo'] ?? '',
            iban: $data['IBAN'] ?? '',
            swiftCode: $data['SwiftCode'] ?? '',
        );
    }
}
