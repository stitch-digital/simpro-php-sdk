<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use DateTimeImmutable;
use Saloon\Http\Response;

/**
 * Webhook subscription DTO.
 */
final readonly class Webhook
{
    /**
     * @param  array<string>  $events
     */
    public function __construct(
        public int $id,
        public string $name,
        public string $callbackUrl,
        public string $secret,
        public ?string $email,
        public string $description,
        public array $events,
        public string $status,
        public ?DateTimeImmutable $dateCreated,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return self::fromArray($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            name: $data['Name'] ?? '',
            callbackUrl: $data['CallbackURL'] ?? '',
            secret: $data['Secret'] ?? '',
            email: $data['Email'] ?? null,
            description: $data['Description'] ?? '',
            events: $data['Events'] ?? [],
            status: $data['Status'] ?? '',
            dateCreated: isset($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
        );
    }
}
