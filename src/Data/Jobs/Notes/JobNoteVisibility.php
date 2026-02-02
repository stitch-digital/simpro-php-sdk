<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\Notes;

final readonly class JobNoteVisibility
{
    public function __construct(
        public ?bool $customer,
        public ?bool $admin,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            customer: $data['Customer'] ?? null,
            admin: $data['Admin'] ?? null,
        );
    }
}
