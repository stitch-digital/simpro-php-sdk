<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Invoices;

use DateTimeImmutable;
use Saloon\Http\Response;

final readonly class Invoice
{
    public function __construct(
        public int $id,
        public ?string $invoiceNo,
        public ?string $status,
        public ?InvoiceCustomer $customer,
        public ?InvoiceSite $site,
        public ?string $orderNo,
        public ?string $description,
        public ?DateTimeImmutable $dateIssued,
        public ?DateTimeImmutable $dateDue,
        public ?InvoiceTotals $totals,
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
            invoiceNo: $data['InvoiceNo'] ?? null,
            status: $data['Status'] ?? null,
            customer: isset($data['Customer']) ? InvoiceCustomer::fromArray($data['Customer']) : null,
            site: isset($data['Site']) ? InvoiceSite::fromArray($data['Site']) : null,
            orderNo: $data['OrderNo'] ?? null,
            description: $data['Description'] ?? null,
            dateIssued: isset($data['DateIssued']) ? new DateTimeImmutable($data['DateIssued']) : null,
            dateDue: isset($data['DateDue']) ? new DateTimeImmutable($data['DateDue']) : null,
            totals: isset($data['Totals']) ? InvoiceTotals::fromArray($data['Totals']) : null,
            dateModified: isset($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
        );
    }
}
