<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;

/**
 * Payment term DTO.
 */
final readonly class PaymentTerm
{
    public function __construct(
        public int $paymentTermId,
        public string $paymentTermName,
        public int $days,
        public string $type,
        public bool $isDefault,
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
            paymentTermId: (int) $data['PaymentTermID'],
            paymentTermName: $data['PaymentTermName'] ?? '',
            days: (int) ($data['Days'] ?? 0),
            type: $data['Type'] ?? '',
            isDefault: (bool) ($data['IsDefault'] ?? false),
        );
    }
}
