<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\Prebuilds\Prebuild;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\Prebuilds\GetPrebuildRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Prebuilds\ListPrebuildsRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class PrebuildLibraryResource extends BaseResource
{
    private const COLUMNS = 'ID,Group,PartNo,Name,SalesTaxCode,Materials,Labour,MaterialMarkup,LabourMarkup,Profit,Margin,TotalEx,TotalInc,Archived,DateModified';

    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListPrebuildsRequest($this->companyId);
        $request->query()->add('columns', self::COLUMNS);
        $request->query()->add('display', 'all');

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    public function get(int $prebuildId): Prebuild
    {
        $request = new GetPrebuildRequest($this->companyId, $prebuildId);
        $request->query()->add('columns', self::COLUMNS);

        return $this->connector->send($request)->dto();
    }
}
