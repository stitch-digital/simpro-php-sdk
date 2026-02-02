<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup\Defaults;

final readonly class DefaultsMandatoryDueDate
{
    public function __construct(
        public bool $serviceJob,
        public bool $projectJob,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            serviceJob: (bool) ($data['ServiceJob'] ?? false),
            projectJob: (bool) ($data['ProjectJob'] ?? false),
        );
    }
}
