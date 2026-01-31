<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\PaymentMethod;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\PaymentMethods\CreatePaymentMethodRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\PaymentMethods\DeletePaymentMethodRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\PaymentMethods\GetPaymentMethodRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\PaymentMethods\ListPaymentMethodsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\PaymentMethods\UpdatePaymentMethodRequest;

/**
 * Resource for managing payment methods.
 *
 * @property AbstractSimproConnector $connector
 */
final class PaymentMethodResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all payment methods.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListPaymentMethodsRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get detailed information for a specific payment method.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $paymentMethodId, ?array $columns = null): PaymentMethod
    {
        $request = new GetPaymentMethodRequest($this->companyId, $paymentMethodId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new payment method.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $request = new CreatePaymentMethodRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update a payment method.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $paymentMethodId, array $data): Response
    {
        $request = new UpdatePaymentMethodRequest($this->companyId, $paymentMethodId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a payment method.
     */
    public function delete(int|string $paymentMethodId): Response
    {
        $request = new DeletePaymentMethodRequest($this->companyId, $paymentMethodId);

        return $this->connector->send($request);
    }
}
