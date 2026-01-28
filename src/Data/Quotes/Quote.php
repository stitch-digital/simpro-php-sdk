<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Quotes;

use DateTimeImmutable;
use Saloon\Http\Response;

final readonly class Quote
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?QuoteSite $site,
        public ?QuoteCustomer $customer,
        public ?string $status,
        public ?string $stage,
        public ?string $orderNo,
        public ?string $description,
        public ?string $notes,
        public ?DateTimeImmutable $dateIssued,
        public ?DateTimeImmutable $dueDate,
        public ?DateTimeImmutable $expiryDate,
        public ?QuoteTotals $totals,
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
            name: $data['Name'] ?? null,
            site: isset($data['Site']) ? QuoteSite::fromArray($data['Site']) : null,
            customer: isset($data['Customer']) ? QuoteCustomer::fromArray($data['Customer']) : null,
            status: $data['Status'] ?? null,
            stage: $data['Stage'] ?? null,
            orderNo: $data['OrderNo'] ?? null,
            description: $data['Description'] ?? null,
            notes: $data['Notes'] ?? null,
            dateIssued: isset($data['DateIssued']) ? new DateTimeImmutable($data['DateIssued']) : null,
            dueDate: isset($data['DueDate']) ? new DateTimeImmutable($data['DueDate']) : null,
            expiryDate: isset($data['ExpiryDate']) ? new DateTimeImmutable($data['ExpiryDate']) : null,
            totals: isset($data['Totals']) ? QuoteTotals::fromArray($data['Totals']) : null,
            dateModified: isset($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
        );
    }
}
