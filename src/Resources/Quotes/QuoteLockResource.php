<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Quotes;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Requests\Quotes\Lock\CreateQuoteLockRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\Lock\DeleteQuoteLockRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class QuoteLockResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $quoteId,
    ) {
        parent::__construct($connector);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data = []): Response
    {
        $request = new CreateQuoteLockRequest($this->companyId, $this->quoteId, $data);

        return $this->connector->send($request);
    }

    public function delete(): Response
    {
        $request = new DeleteQuoteLockRequest($this->companyId, $this->quoteId);

        return $this->connector->send($request);
    }
}
