<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup\Defaults;

final readonly class DefaultsSystem
{
    public function __construct(
        public DefaultsGeneral $general,
        public DefaultsJobsQuotes $jobsQuotes,
        public DefaultsJobs $jobs,
        public DefaultsQuotes $quotes,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            general: DefaultsGeneral::fromArray($data['General'] ?? []),
            jobsQuotes: DefaultsJobsQuotes::fromArray($data['JobsQuotes'] ?? []),
            jobs: DefaultsJobs::fromArray($data['Jobs'] ?? []),
            quotes: DefaultsQuotes::fromArray($data['Quotes'] ?? []),
        );
    }
}
