<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\JobCostCenters;

use DateTimeImmutable;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class JobCostCenterListItem
{
    public function __construct(
        public int $id,
        public ?Reference $costCenter,
        public ?string $name,
        public ?JobCostCenterJob $job,
        public ?Reference $section,
        public ?DateTimeImmutable $dateModified,
        public ?string $href,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            costCenter: isset($data['CostCenter']) ? Reference::fromArray($data['CostCenter']) : null,
            name: $data['Name'] ?? null,
            job: isset($data['Job']) ? JobCostCenterJob::fromArray($data['Job']) : null,
            section: isset($data['Section']) ? Reference::fromArray($data['Section']) : null,
            dateModified: isset($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
            href: $data['_href'] ?? null,
        );
    }
}
