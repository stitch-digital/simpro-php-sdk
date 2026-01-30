<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;

final readonly class Defaults
{
    public function __construct(
        public ?int $defaultTaxCodeId = null,
        public ?int $defaultPaymentTermId = null,
        public ?int $defaultPaymentMethodId = null,
        public ?int $defaultCustomerGroupId = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            defaultTaxCodeId: $data['DefaultTaxCode']['ID'] ?? null,
            defaultPaymentTermId: $data['DefaultPaymentTerm']['ID'] ?? null,
            defaultPaymentMethodId: $data['DefaultPaymentMethod']['ID'] ?? null,
            defaultCustomerGroupId: $data['DefaultCustomerGroup']['ID'] ?? null,
        );
    }

    public static function fromResponse(Response $response): self
    {
        /** @var array<string, mixed> $data */
        $data = $response->json();

        return self::fromArray($data);
    }
}
