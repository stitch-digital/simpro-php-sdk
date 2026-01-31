<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Tasks\Categories;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteTaskCategoryRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $taskCategoryId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/tasks/categories/{$this->taskCategoryId}";
    }
}
