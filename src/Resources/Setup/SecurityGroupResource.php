<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\SecurityGroup;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\SecurityGroups\GetSecurityGroupRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\SecurityGroups\ListSecurityGroupsRequest;

/**
 * Resource for managing SecurityGroups.
 *
 * @property AbstractSimproConnector $connector
 */
final class SecurityGroupResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListSecurityGroupsRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific item.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $securityGroupId, ?array $columns = null): SecurityGroup
    {
        $request = new GetSecurityGroupRequest($this->companyId, $securityGroupId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }
}
