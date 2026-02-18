<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Quotes\Sections;

use DateTimeImmutable;
use Saloon\Http\Response;

final readonly class QuoteSection
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $description,
        public ?bool $isVariation,
        public ?int $displayOrder,
        public ?DateTimeImmutable $dateModified,
    ) {}

    public static function fromResponse(Response $response): self
    {
        return self::fromArray($response->json());
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            name: $data['Name'] ?? null,
            description: $data['Description'] ?? null,
            isVariation: $data['IsVariation'] ?? null,
            displayOrder: isset($data['DisplayOrder']) ? (int) $data['DisplayOrder'] : null,
            dateModified: ! empty($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
        );
    }
}
