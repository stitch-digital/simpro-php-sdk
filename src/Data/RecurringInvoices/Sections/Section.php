<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\RecurringInvoices\Sections;

use DateTimeImmutable;
use Saloon\Http\Response;

/**
 * A recurring invoice section. Only Project recurring invoices carry a name and description.
 */
final readonly class Section
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $description,
        public ?int $displayOrder,
        public ?DateTimeImmutable $dateModified,
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
            id: (int) $data['ID'],
            name: $data['Name'] ?? null,
            description: $data['Description'] ?? null,
            displayOrder: isset($data['DisplayOrder']) ? (int) $data['DisplayOrder'] : null,
            dateModified: ! empty($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
        );
    }
}
