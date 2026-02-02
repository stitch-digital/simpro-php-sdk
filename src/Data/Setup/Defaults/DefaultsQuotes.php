<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup\Defaults;

final readonly class DefaultsQuotes
{
    public function __construct(
        public DefaultsQuotesMandatoryDueDate $mandatoryDueDateOnCreation,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            mandatoryDueDateOnCreation: DefaultsQuotesMandatoryDueDate::fromArray(
                $data['MandatoryDueDateOnCreation'] ?? []
            ),
        );
    }
}
