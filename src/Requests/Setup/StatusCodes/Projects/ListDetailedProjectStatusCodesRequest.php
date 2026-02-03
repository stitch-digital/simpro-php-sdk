<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\Projects;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\ProjectStatusCode;

/**
 * List all project status codes with full details.
 */
final class ListDetailedProjectStatusCodesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    /**
     * All columns available for project status codes.
     */
    private const DETAILED_COLUMNS = [
        'ID',
        'Name',
        'Color',
        'Priority',
    ];

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/statusCodes/projects/";
    }

    /**
     * @return array<string, string>
     */
    protected function defaultQuery(): array
    {
        return [
            'columns' => implode(',', self::DETAILED_COLUMNS),
        ];
    }

    /**
     * @return array<ProjectStatusCode>
     */
    public function createDtoFromResponse(Response $response): array
    {
        /** @var array<int, array<string, mixed>> $data */
        $data = $response->json();

        return array_map(
            fn (array $item): ProjectStatusCode => ProjectStatusCode::fromArray($item),
            $data
        );
    }
}
