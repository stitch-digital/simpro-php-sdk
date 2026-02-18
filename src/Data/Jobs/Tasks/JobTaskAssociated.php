<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\Tasks;

final readonly class JobTaskAssociated
{
    public function __construct(
        public ?JobTaskAssociatedJob $job,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            job: ! empty($data['Job']) ? JobTaskAssociatedJob::fromArray($data['Job']) : null,
        );
    }
}
