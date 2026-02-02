<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup\Defaults;

final readonly class DefaultsQuotesMandatoryDueDate
{
    public function __construct(
        public bool $serviceQuote,
        public bool $projectQuote,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            serviceQuote: (bool) ($data['ServiceQuote'] ?? false),
            projectQuote: (bool) ($data['ProjectQuote'] ?? false),
        );
    }
}
