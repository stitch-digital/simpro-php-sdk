<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup\Defaults;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class DefaultsJobs
{
    public function __construct(
        public ?Reference $warrantyCostCenter,
        public DefaultsMandatoryDueDate $mandatoryDueDateOnCreation,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            warrantyCostCenter: isset($data['WarrantyCostCenter']['ID'])
                ? Reference::fromArray($data['WarrantyCostCenter'])
                : null,
            mandatoryDueDateOnCreation: DefaultsMandatoryDueDate::fromArray(
                $data['MandatoryDueDateOnCreation'] ?? []
            ),
        );
    }
}
