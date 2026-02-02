<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\Notes;

final readonly class JobNoteAttachment
{
    public function __construct(
        public ?string $href,
        public ?string $fileName,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            href: $data['_href'] ?? null,
            fileName: $data['FileName'] ?? null,
        );
    }
}
