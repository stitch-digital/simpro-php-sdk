<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\Tasks;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class JobTaskCustomField
{
    public function __construct(
        public ?Reference $customField,
        public mixed $value,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            customField: isset($data['CustomField']) ? Reference::fromArray($data['CustomField']) : null,
            value: $data['Value'] ?? null,
        );
    }
}
