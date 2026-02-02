<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;

final readonly class ResponseTime
{
    public function __construct(
        public int $iD,
        public string $name,
        public int $days,
        public int $hours,
        public int $minutes,
        public bool $includeWeekends,
        public bool $archived,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            iD: (int) ($data['ID'] ?? 0),
            name: $data['Name'] ?? '',
            days: (int) ($data['Days'] ?? 0),
            hours: (int) ($data['Hours'] ?? 0),
            minutes: (int) ($data['Minutes'] ?? 0),
            includeWeekends: (bool) ($data['IncludeWeekends'] ?? false),
            archived: (bool) ($data['Archived'] ?? false),
        );
    }

    public static function fromResponse(Response $response): self
    {
        /** @var array<string, mixed> $data */
        $data = $response->json();

        return self::fromArray($data);
    }
}
