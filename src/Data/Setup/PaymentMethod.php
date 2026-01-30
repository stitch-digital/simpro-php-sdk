<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;

/**
 * Payment method DTO.
 */
final readonly class PaymentMethod
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $accountNo = null,
        public ?string $type = null,
        public ?float $financeCharge = null,
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
            accountNo: $data['AccountNo'] ?? null,
            type: $data['Type'] ?? null,
            financeCharge: isset($data['FinanceCharge']) ? (float) $data['FinanceCharge'] : null,
        );
    }
}
