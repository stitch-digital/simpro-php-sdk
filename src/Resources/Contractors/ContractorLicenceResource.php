<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Contractors;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Employees\Licences\Licence;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Contractors\Licences\CreateContractorLicenceRequest;
use Simpro\PhpSdk\Simpro\Requests\Contractors\Licences\DeleteContractorLicenceRequest;
use Simpro\PhpSdk\Simpro\Requests\Contractors\Licences\GetContractorLicenceRequest;
use Simpro\PhpSdk\Simpro\Requests\Contractors\Licences\ListContractorLicencesRequest;
use Simpro\PhpSdk\Simpro\Requests\Contractors\Licences\UpdateContractorLicenceRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class ContractorLicenceResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $contractorId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all licences for this contractor.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListContractorLicencesRequest($this->companyId, $this->contractorId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific licence.
     */
    public function get(int|string $licenceId): Licence
    {
        $request = new GetContractorLicenceRequest($this->companyId, $this->contractorId, $licenceId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new licence.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created licence
     */
    public function create(array $data): int
    {
        $request = new CreateContractorLicenceRequest($this->companyId, $this->contractorId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing licence.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $licenceId, array $data): Response
    {
        $request = new UpdateContractorLicenceRequest($this->companyId, $this->contractorId, $licenceId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a licence.
     */
    public function delete(int|string $licenceId): Response
    {
        $request = new DeleteContractorLicenceRequest($this->companyId, $this->contractorId, $licenceId);

        return $this->connector->send($request);
    }
}
