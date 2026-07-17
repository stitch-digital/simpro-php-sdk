<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\Logs;

use DateTimeImmutable;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;

final readonly class JobLog
{
    public function __construct(
        public int $id,
        public ?int $jobId,
        public ?string $message,
        public ?StaffReference $staff,
        public ?DateTimeImmutable $dateLogged,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            jobId: isset($data['JobID']) ? (int) $data['JobID'] : null,
            message: $data['Message'] ?? null,
            staff: ! empty($data['Staff']) ? StaffReference::fromArray($data['Staff']) : null,
            dateLogged: ! empty($data['DateLogged']) ? new DateTimeImmutable($data['DateLogged']) : null,
        );
    }
}
