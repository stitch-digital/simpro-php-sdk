<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\Tasks;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;

final readonly class JobTask
{
    /**
     * @param  array<StaffReference>|null  $assignees
     * @param  array<JobTaskSubTask>|null  $subTasks
     * @param  array<JobTaskCustomField>|null  $customFields
     */
    public function __construct(
        public int $id,
        public ?string $subject,
        public ?StaffReference $createdBy,
        public ?StaffReference $assignedTo,
        public ?array $assignees,
        public ?bool $assignedToCustomer,
        public ?StaffReference $completedBy,
        public ?JobTaskAssociated $associated,
        public ?bool $isBillable,
        public ?bool $showOnWorkOrder,
        public ?JobTaskEmailNotifications $emailNotifications,
        public ?string $description,
        public ?string $notes,
        public ?JobTaskStatus $status,
        public ?JobTaskPriority $priority,
        public ?JobTaskTime $estimated,
        public ?JobTaskTime $actual,
        public ?array $subTasks,
        public ?array $customFields,
        public ?int $percentComplete,
        public ?DateTimeImmutable $dateModified,
    ) {}

    public static function fromResponse(Response $response): self
    {
        return self::fromArray($response->json());
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            subject: $data['Subject'] ?? null,
            createdBy: ! empty($data['CreatedBy']) ? StaffReference::fromArray($data['CreatedBy']) : null,
            assignedTo: ! empty($data['AssignedTo']) ? StaffReference::fromArray($data['AssignedTo']) : null,
            assignees: isset($data['Assignees']) ? array_map(
                fn (array $item) => StaffReference::fromArray($item),
                $data['Assignees']
            ) : null,
            assignedToCustomer: $data['AssignedToCustomer'] ?? null,
            completedBy: ! empty($data['CompletedBy']) ? StaffReference::fromArray($data['CompletedBy']) : null,
            associated: ! empty($data['Associated']) ? JobTaskAssociated::fromArray($data['Associated']) : null,
            isBillable: $data['IsBillable'] ?? null,
            showOnWorkOrder: $data['ShowOnWorkOrder'] ?? null,
            emailNotifications: ! empty($data['EmailNotifications']) ? JobTaskEmailNotifications::fromArray($data['EmailNotifications']) : null,
            description: $data['Description'] ?? null,
            notes: $data['Notes'] ?? null,
            status: ! empty($data['Status']) ? JobTaskStatus::fromArray($data['Status']) : null,
            priority: ! empty($data['Priority']) ? JobTaskPriority::fromArray($data['Priority']) : null,
            estimated: ! empty($data['Estimated']) ? JobTaskTime::fromArray($data['Estimated']) : null,
            actual: ! empty($data['Actual']) ? JobTaskTime::fromArray($data['Actual']) : null,
            subTasks: isset($data['SubTasks']) ? array_map(
                fn (array $item) => JobTaskSubTask::fromArray($item),
                $data['SubTasks']
            ) : null,
            customFields: isset($data['CustomFields']) ? array_map(
                fn (array $item) => JobTaskCustomField::fromArray($item),
                $data['CustomFields']
            ) : null,
            percentComplete: isset($data['PercentComplete']) ? (int) $data['PercentComplete'] : null,
            dateModified: ! empty($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
        );
    }
}
