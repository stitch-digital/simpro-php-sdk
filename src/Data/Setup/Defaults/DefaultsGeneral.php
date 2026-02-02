<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup\Defaults;

final readonly class DefaultsGeneral
{
    public function __construct(
        public string $dateFormat,
        public string $timeFormat,
        public string $thousandsSeparator,
        public string $negativeNumberFormat,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            dateFormat: $data['DateFormat'] ?? '',
            timeFormat: $data['TimeFormat'] ?? '',
            thousandsSeparator: $data['ThousandsSeparator'] ?? '',
            negativeNumberFormat: $data['NegativeNumberFormat'] ?? '',
        );
    }
}
