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
        public ?DefaultsSystem $system,
        public ?DefaultsFinancial $financial,
        public ?DefaultsSchedule $schedule,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            system: ! empty($data['System']) ? DefaultsSystem::fromArray($data['System']) : null,
            financial: ! empty($data['Financial']) ? DefaultsFinancial::fromArray($data['Financial']) : null,
            schedule: ! empty($data['Schedule']) ? DefaultsSchedule::fromArray($data['Schedule']) : null,
        );
    }

    public static function fromResponse(Response $response): self
    {
        /** @var array<string, mixed> $data */
        $data = $response->json();

        return self::fromArray($data);
    }
}
