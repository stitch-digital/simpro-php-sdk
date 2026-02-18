<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Quotes;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\Attachments\AttachmentFile;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Quotes\Attachments\Files\CreateQuoteAttachmentFileRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\Attachments\Files\GetQuoteAttachmentFileRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\Attachments\Files\ListQuoteAttachmentFilesRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class QuoteAttachmentFileResource extends BaseResource
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
        $request = new ListQuoteAttachmentFilesRequest($this->companyId, $this->quoteId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    public function get(int|string $fileId): AttachmentFile
    {
        $request = new GetQuoteAttachmentFileRequest($this->companyId, $this->quoteId, $fileId);

        return $this->connector->send($request)->dto();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $request = new CreateQuoteAttachmentFileRequest($this->companyId, $this->quoteId, $data);

        return $this->connector->send($request)->dto();
    }
}
