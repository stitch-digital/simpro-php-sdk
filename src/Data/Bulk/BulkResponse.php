<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Bulk;

use Saloon\Http\Response;

final readonly class BulkResponse
{
    /**
     * @param  array<int, BulkResponseItem>  $items
     */
    public function __construct(
        public array $items,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return new self(
            items: array_map(
                fn (array $item) => BulkResponseItem::fromArray($item),
                $data,
            ),
        );
    }

    /**
     * @return array<int, int|string>
     */
    public function resourceIds(): array
    {
        return array_map(fn (BulkResponseItem $item) => $item->resourceId, $this->items);
    }

    public function allSuccessful(): bool
    {
        return $this->failures() === [];
    }

    /**
     * @return array<int, BulkResponseItem>
     */
    public function failures(): array
    {
        return array_values(
            array_filter(
                $this->items,
                fn (BulkResponseItem $item) => ! $item->isSuccessful(),
            ),
        );
    }
}
