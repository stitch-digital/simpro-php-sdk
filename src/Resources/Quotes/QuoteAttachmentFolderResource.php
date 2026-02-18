<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Quotes;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\Attachments\AttachmentFolder;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Quotes\Attachments\Folders\CreateQuoteAttachmentFolderRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\Attachments\Folders\GetQuoteAttachmentFolderRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\Attachments\Folders\ListQuoteAttachmentFoldersRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class QuoteAttachmentFolderResource extends BaseResource
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
        $request = new ListQuoteAttachmentFoldersRequest($this->companyId, $this->quoteId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    public function get(int|string $folderId): AttachmentFolder
    {
        $request = new GetQuoteAttachmentFolderRequest($this->companyId, $this->quoteId, $folderId);

        return $this->connector->send($request)->dto();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $request = new CreateQuoteAttachmentFolderRequest($this->companyId, $this->quoteId, $data);

        return $this->connector->send($request)->dto();
    }
}
