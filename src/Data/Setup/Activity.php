<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class Activity
{
    public function __construct(
        public int $id,
        public string $name,
        public bool $billable,
        public bool $archived,
        public ?Reference $scheduleRate,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            name: $data['Name'] ?? '',
            billable: (bool) ($data['Billable'] ?? false),
            archived: (bool) ($data['Archived'] ?? false),
            scheduleRate: ! empty($data['ScheduleRate']['ID']) ? Reference::fromArray($data['ScheduleRate']) : null,
        );
    }

    public static function fromResponse(Response $response): self
    {
        /** @var array<string, mixed> $data */
        $data = $response->json();

        return self::fromArray($data);
    }
}
