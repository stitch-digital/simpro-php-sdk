<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\ContractorInvoices;

use Simpro\PhpSdk\Simpro\Data\Contractors\ContractorPaymentTerms;

final readonly class ContractorInvoiceContractorBanking
{
    public function __construct(
        public ?string $accountName = null,
        public ?string $routingNo = null,
        public ?string $accountNo = null,
        public ?int $paymentTermId = null,
        public ?ContractorPaymentTerms $paymentTerms = null,
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
