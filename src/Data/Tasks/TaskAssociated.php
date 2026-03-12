<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Tasks;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class TaskAssociated
{
    public function __construct(
        public ?TaskAssociatedJob $job = null,
        public ?Reference $quote = null,
        public ?TaskAssociatedCustomer $customer = null,
        public ?Reference $contact = null,
        public ?Reference $site = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            job: isset($data['Job']) && is_array($data['Job']) && ! empty($data['Job'])
                ? TaskAssociatedJob::fromArray($data['Job'])
                : null,
            quote: isset($data['Quote']) && is_array($data['Quote']) && ! empty($data['Quote'])
                ? Reference::fromArray($data['Quote'])
                : null,
            customer: isset($data['Customer']) && is_array($data['Customer']) && ! empty($data['Customer'])
                ? TaskAssociatedCustomer::fromArray($data['Customer'])
                : null,
            contact: isset($data['Contact']) && is_array($data['Contact']) && ! empty($data['Contact'])
                ? Reference::fromArray($data['Contact'])
                : null,
            site: isset($data['Site']) && is_array($data['Site']) && ! empty($data['Site'])
                ? Reference::fromArray($data['Site'])
                : null,
        );
    }
}
