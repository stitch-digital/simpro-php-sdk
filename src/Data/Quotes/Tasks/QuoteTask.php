<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Quotes\Tasks;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;

final readonly class QuoteTask
{
    /**
     * @param  array<StaffReference>|null  $assignees
     */
    public function __construct(
        public int $id,
        public ?string $subject,
        public ?string $description,
        public ?string $notes,
        public ?StaffReference $createdBy,
        public ?StaffReference $assignedTo,
        public ?array $assignees,
        public ?bool $isBillable,
        public ?int $percentComplete,
        public ?DateTimeImmutable $dateModified,
    ) {}

    public static function fromResponse(Response $response): self
    {
        return self::fromArray($response->json());
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            subject: $data['Subject'] ?? null,
            description: $data['Description'] ?? null,
            notes: $data['Notes'] ?? null,
            createdBy: ! empty($data['CreatedBy']) ? StaffReference::fromArray($data['CreatedBy']) : null,
            assignedTo: ! empty($data['AssignedTo']) ? StaffReference::fromArray($data['AssignedTo']) : null,
            assignees: isset($data['Assignees']) ? array_map(
                fn (array $item) => StaffReference::fromArray($item),
                $data['Assignees']
            ) : null,
            isBillable: $data['IsBillable'] ?? null,
            percentComplete: isset($data['PercentComplete']) ? (int) $data['PercentComplete'] : null,
            dateModified: ! empty($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
        );
    }
}
