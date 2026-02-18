<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Quotes;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Quotes\QuoteAttachmentFileResource;
use Simpro\PhpSdk\Simpro\Resources\Quotes\QuoteAttachmentFolderResource;
use Simpro\PhpSdk\Simpro\Resources\Quotes\QuoteCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Quotes\QuoteLockResource;
use Simpro\PhpSdk\Simpro\Resources\Quotes\QuoteNoteResource;
use Simpro\PhpSdk\Simpro\Resources\Quotes\QuoteSectionResource;
use Simpro\PhpSdk\Simpro\Resources\Quotes\QuoteTaskResource;
use Simpro\PhpSdk\Simpro\Resources\Quotes\QuoteTimelineResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

final class QuoteScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int $companyId,
        private readonly int|string $quoteId,
    ) {
        parent::__construct($connector, $companyId);
    }

    public function attachmentFiles(): QuoteAttachmentFileResource
    {
        return new QuoteAttachmentFileResource($this->connector, $this->companyId, $this->quoteId);
    }

    public function attachmentFolders(): QuoteAttachmentFolderResource
    {
        return new QuoteAttachmentFolderResource($this->connector, $this->companyId, $this->quoteId);
    }

    public function customFields(): QuoteCustomFieldResource
    {
        return new QuoteCustomFieldResource($this->connector, $this->companyId, $this->quoteId);
    }

    public function lock(): QuoteLockResource
    {
        return new QuoteLockResource($this->connector, $this->companyId, $this->quoteId);
    }

    public function notes(): QuoteNoteResource
    {
        return new QuoteNoteResource($this->connector, $this->companyId, $this->quoteId);
    }

    public function sections(): QuoteSectionResource
    {
        return new QuoteSectionResource($this->connector, $this->companyId, $this->quoteId);
    }

    public function section(int|string $sectionId): QuoteSectionScope
    {
        return new QuoteSectionScope($this->connector, $this->companyId, $this->quoteId, $sectionId);
    }

    public function tasks(): QuoteTaskResource
    {
        return new QuoteTaskResource($this->connector, $this->companyId, $this->quoteId);
    }

    public function timelines(): QuoteTimelineResource
    {
        return new QuoteTimelineResource($this->connector, $this->companyId, $this->quoteId);
    }
}
