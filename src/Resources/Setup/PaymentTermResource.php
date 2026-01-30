<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\PaymentTerm;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\PaymentTerms\CreatePaymentTermRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\PaymentTerms\DeletePaymentTermRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\PaymentTerms\GetPaymentTermRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\PaymentTerms\ListPaymentTermsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\PaymentTerms\UpdatePaymentTermRequest;

/**
 * Resource for managing payment terms.
 *
 * @property AbstractSimproConnector $connector
 */
final class PaymentTermResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all payment terms.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListPaymentTermsRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get detailed information for a specific payment term.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $paymentTermId, ?array $columns = null): PaymentTerm
    {
        $request = new GetPaymentTermRequest($this->companyId, $paymentTermId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new payment term.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $request = new CreatePaymentTermRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update a payment term.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $paymentTermId, array $data): Response
    {
        $request = new UpdatePaymentTermRequest($this->companyId, $paymentTermId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a payment term.
     */
    public function delete(int|string $paymentTermId): Response
    {
        $request = new DeletePaymentTermRequest($this->companyId, $paymentTermId);

        return $this->connector->send($request);
    }
}
