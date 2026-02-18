<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup\Defaults;

final readonly class DefaultsSystem
{
    public function __construct(
        public ?DefaultsGeneral $general,
        public ?DefaultsJobsQuotes $jobsQuotes,
        public ?DefaultsJobs $jobs,
        public ?DefaultsQuotes $quotes,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            general: ! empty($data['General']) ? DefaultsGeneral::fromArray($data['General']) : null,
            jobsQuotes: ! empty($data['JobsQuotes']) ? DefaultsJobsQuotes::fromArray($data['JobsQuotes']) : null,
            jobs: ! empty($data['Jobs']) ? DefaultsJobs::fromArray($data['Jobs']) : null,
            quotes: ! empty($data['Quotes']) ? DefaultsQuotes::fromArray($data['Quotes']) : null,
        );
    }
}
