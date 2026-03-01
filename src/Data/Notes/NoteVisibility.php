<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Notes;

final readonly class NoteVisibility
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
