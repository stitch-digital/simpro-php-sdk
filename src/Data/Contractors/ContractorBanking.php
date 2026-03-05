<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Contractors;

final readonly class ContractorBanking
{
    public function __construct(
        public ?string $accountName,
        public ?string $routingNo,
        public ?string $accountNo,
        public ?int $paymentTermId,
        public ?ContractorPaymentTerms $paymentTerms,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            accountName: $data['AccountName'] ?? null,
            routingNo: $data['RoutingNo'] ?? null,
            accountNo: $data['AccountNo'] ?? null,
            paymentTermId: isset($data['PaymentTermID']) ? (int) $data['PaymentTermID'] : null,
            paymentTerms: ! empty($data['PaymentTerms']) ? ContractorPaymentTerms::fromArray($data['PaymentTerms']) : null,
        );
    }
}
