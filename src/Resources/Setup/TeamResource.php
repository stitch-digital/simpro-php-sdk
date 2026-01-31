<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\Team;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\Teams\GetTeamRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Teams\ListDetailedTeamsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Teams\ListTeamsRequest;

/**
 * Resource for managing Teams.
 *
 * @property AbstractSimproConnector $connector
 */
final class TeamResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all teams with basic information (ID and Name only).
     * This is a lightweight method for quick lookups and dropdowns.
     *
     * Returns a QueryBuilder that supports fluent search, ordering, and filtering.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListTeamsRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all teams with complete information (all fields).
     * Use this when you need detailed team data including availability,
     * cost centers, members, and zones without making individual get() calls.
     *
     * Returns a QueryBuilder that supports fluent search, ordering, and filtering.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListDetailedTeamsRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get detailed information for a specific team.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $teamId, ?array $columns = null): Team
    {
        $request = new GetTeamRequest($this->companyId, $teamId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }
}
