<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers\Contacts;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;

/**
 * DTO for a single contact (detailed view).
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/customers/{customerID}/contacts/{contactID}
 */
final readonly class Contact
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
        public ?bool $quoteContact,
        public ?bool $jobContact,
        public ?bool $invoiceContact,
        public ?bool $statementContact,
        public ?bool $primaryStatementContact,
        public ?bool $primaryInvoiceContact,
        public ?bool $primaryJobContact,
        public ?bool $primaryQuoteContact,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return self::fromArray($data);
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
            dateModified: isset($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
            quoteContact: $data['QuoteContact'] ?? null,
            jobContact: $data['JobContact'] ?? null,
            invoiceContact: $data['InvoiceContact'] ?? null,
            statementContact: $data['StatementContact'] ?? null,
            primaryStatementContact: $data['PrimaryStatementContact'] ?? null,
            primaryInvoiceContact: $data['PrimaryInvoiceContact'] ?? null,
            primaryJobContact: $data['PrimaryJobContact'] ?? null,
            primaryQuoteContact: $data['PrimaryQuoteContact'] ?? null,
        );
    }
}
