<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Notes\ListNoteCustomersDetailedRequest;
use Simpro\PhpSdk\Simpro\Requests\Notes\ListNoteCustomersRequest;
use Simpro\PhpSdk\Simpro\Requests\Notes\ListNoteJobsDetailedRequest;
use Simpro\PhpSdk\Simpro\Requests\Notes\ListNoteJobsRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class NoteResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all customer notes across all customers.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function customers(array $filters = []): QueryBuilder
    {
        $request = new ListNoteCustomersRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all customer notes with detailed information.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function customersDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListNoteCustomersDetailedRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all job notes across all jobs.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function jobs(array $filters = []): QueryBuilder
    {
        $request = new ListNoteJobsRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all job notes with detailed information.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function jobsDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListNoteJobsDetailedRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }
}
