<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Support;

/**
 * Fluent interface for building nested API endpoint paths.
 *
 * @example
 * $path = PathBuilder::make()
 *     ->companies(0)
 *     ->jobs(123)
 *     ->sections(1)
 *     ->costCenters(5)
 *     ->build();
 * // Returns: "/api/v1.0/companies/0/jobs/123/sections/1/costCenters/5"
 */
final class PathBuilder
{
    private const API_PREFIX = '/api/v1.0';

    /** @var array<string> */
    private array $segments = [];

    private function __construct() {}

    /**
     * Create a new PathBuilder instance.
     */
    public static function make(): self
    {
        return new self;
    }

    /**
     * Add a companies segment.
     */
    public function companies(int|string $companyId): self
    {
        $this->segments[] = "companies/{$companyId}";

        return $this;
    }

    /**
     * Add a jobs segment.
     */
    public function jobs(int|string|null $jobId = null): self
    {
        $this->segments[] = $jobId !== null ? "jobs/{$jobId}" : 'jobs';

        return $this;
    }

    /**
     * Add a quotes segment.
     */
    public function quotes(int|string|null $quoteId = null): self
    {
        $this->segments[] = $quoteId !== null ? "quotes/{$quoteId}" : 'quotes';

        return $this;
    }

    /**
     * Add an invoices segment.
     */
    public function invoices(int|string|null $invoiceId = null): self
    {
        $this->segments[] = $invoiceId !== null ? "invoices/{$invoiceId}" : 'invoices';

        return $this;
    }

    /**
     * Add a customers segment.
     */
    public function customers(int|string|null $customerId = null): self
    {
        $this->segments[] = $customerId !== null ? "customers/{$customerId}" : 'customers';

        return $this;
    }

    /**
     * Add an employees segment.
     */
    public function employees(int|string|null $employeeId = null): self
    {
        $this->segments[] = $employeeId !== null ? "employees/{$employeeId}" : 'employees';

        return $this;
    }

    /**
     * Add a schedules segment.
     */
    public function schedules(int|string|null $scheduleId = null): self
    {
        $this->segments[] = $scheduleId !== null ? "schedules/{$scheduleId}" : 'schedules';

        return $this;
    }

    /**
     * Add a sections segment.
     */
    public function sections(int|string|null $sectionId = null): self
    {
        $this->segments[] = $sectionId !== null ? "sections/{$sectionId}" : 'sections';

        return $this;
    }

    /**
     * Add a costCenters segment.
     */
    public function costCenters(int|string|null $costCenterId = null): self
    {
        $this->segments[] = $costCenterId !== null ? "costCenters/{$costCenterId}" : 'costCenters';

        return $this;
    }

    /**
     * Add a customFields segment.
     */
    public function customFields(int|string|null $customFieldId = null): self
    {
        $this->segments[] = $customFieldId !== null ? "customFields/{$customFieldId}" : 'customFields';

        return $this;
    }

    /**
     * Add an attachments/files segment.
     */
    public function attachmentFiles(int|string|null $fileId = null): self
    {
        $this->segments[] = $fileId !== null ? "attachments/files/{$fileId}" : 'attachments/files';

        return $this;
    }

    /**
     * Add an attachments/folders segment.
     */
    public function attachmentFolders(int|string|null $folderId = null): self
    {
        $this->segments[] = $folderId !== null ? "attachments/folders/{$folderId}" : 'attachments/folders';

        return $this;
    }

    /**
     * Add a notes segment.
     */
    public function notes(int|string|null $noteId = null): self
    {
        $this->segments[] = $noteId !== null ? "notes/{$noteId}" : 'notes';

        return $this;
    }

    /**
     * Add a contacts segment.
     */
    public function contacts(int|string|null $contactId = null): self
    {
        $this->segments[] = $contactId !== null ? "contacts/{$contactId}" : 'contacts';

        return $this;
    }

    /**
     * Add a sites segment.
     */
    public function sites(int|string|null $siteId = null): self
    {
        $this->segments[] = $siteId !== null ? "sites/{$siteId}" : 'sites';

        return $this;
    }

    /**
     * Add a vendors segment.
     */
    public function vendors(int|string|null $vendorId = null): self
    {
        $this->segments[] = $vendorId !== null ? "vendors/{$vendorId}" : 'vendors';

        return $this;
    }

    /**
     * Add a catalogs segment.
     */
    public function catalogs(int|string|null $catalogId = null): self
    {
        $this->segments[] = $catalogId !== null ? "catalogs/{$catalogId}" : 'catalogs';

        return $this;
    }

    /**
     * Add a generic segment.
     */
    public function segment(string $name, int|string|null $id = null): self
    {
        $this->segments[] = $id !== null ? "{$name}/{$id}" : $name;

        return $this;
    }

    /**
     * Build the final path string.
     *
     * @param  bool  $trailingSlash  Whether to add a trailing slash for list endpoints
     */
    public function build(bool $trailingSlash = false): string
    {
        $path = self::API_PREFIX.'/'.implode('/', $this->segments);

        return $trailingSlash ? $path.'/' : $path;
    }

    /**
     * Build and return the path as a string.
     */
    public function __toString(): string
    {
        return $this->build();
    }
}
