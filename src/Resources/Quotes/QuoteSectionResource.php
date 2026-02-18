<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Quotes;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Quotes\Sections\QuoteSection;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Quotes\Sections\CreateQuoteSectionRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\Sections\DeleteQuoteSectionRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\Sections\GetQuoteSectionRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\Sections\ListQuoteSectionsRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\Sections\UpdateQuoteSectionRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class QuoteSectionResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $quoteId,
    ) {
        parent::__construct($connector);
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListQuoteSectionsRequest($this->companyId, $this->quoteId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    public function get(int|string $sectionId): QuoteSection
    {
        $request = new GetQuoteSectionRequest($this->companyId, $this->quoteId, $sectionId);

        return $this->connector->send($request)->dto();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $request = new CreateQuoteSectionRequest($this->companyId, $this->quoteId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $sectionId, array $data): Response
    {
        $request = new UpdateQuoteSectionRequest($this->companyId, $this->quoteId, $sectionId, $data);

        return $this->connector->send($request);
    }

    public function delete(int|string $sectionId): Response
    {
        $request = new DeleteQuoteSectionRequest($this->companyId, $this->quoteId, $sectionId);

        return $this->connector->send($request);
    }
}
