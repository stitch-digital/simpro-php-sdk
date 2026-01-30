<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * DTO for customer banking/payment details.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/customers/individuals/{customerID}
 */
final readonly class CustomerBankingDetails
{
    public function __construct(
        public string $accountName,
        public string $routingNo,
        public string $accountNo,
        public ?Reference $paymentMethod,
        public int $paymentTermID,
        public ?CustomerPaymentTerms $paymentTerms,
        public float $creditLimit,
        public bool $onStop,
        public string $retention,
        public bool $vendorOrderNoRequired,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            accountName: $data['AccountName'] ?? '',
            routingNo: $data['RoutingNo'] ?? '',
            accountNo: $data['AccountNo'] ?? '',
            paymentMethod: isset($data['PaymentMethod']) ? Reference::fromArray($data['PaymentMethod']) : null,
            paymentTermID: $data['PaymentTermID'] ?? 0,
            paymentTerms: isset($data['PaymentTerms']) ? CustomerPaymentTerms::fromArray($data['PaymentTerms']) : null,
            creditLimit: isset($data['CreditLimit']) ? (float) $data['CreditLimit'] : 0.0,
            onStop: $data['OnStop'] ?? false,
            retention: $data['Retention'] ?? '',
            vendorOrderNoRequired: $data['VendorOrderNoRequired'] ?? false,
        );
    }
}
