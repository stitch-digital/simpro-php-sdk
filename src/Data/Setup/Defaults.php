<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\Defaults\DefaultsFinancial;
use Simpro\PhpSdk\Simpro\Data\Setup\Defaults\DefaultsSchedule;
use Simpro\PhpSdk\Simpro\Data\Setup\Defaults\DefaultsSystem;

final readonly class Defaults
{
    public function __construct(
        public DefaultsSystem $system,
        public DefaultsFinancial $financial,
        public DefaultsSchedule $schedule,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            system: DefaultsSystem::fromArray($data['System'] ?? []),
            financial: DefaultsFinancial::fromArray($data['Financial'] ?? []),
            schedule: DefaultsSchedule::fromArray($data['Schedule'] ?? []),
        );
    }

    public static function fromResponse(Response $response): self
    {
        /** @var array<string, mixed> $data */
        $data = $response->json();

        return self::fromArray($data);
    }
}
