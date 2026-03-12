<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Tasks;

use DateTimeImmutable;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class TaskListDetailedItem
{
    /**
     * @param  array<TaskAssignee>|null  $assignees
     * @param  array<int>|null  $subTasks
     * @param  array<CustomField>|null  $customFields
     */
    public function __construct(
        public int $id,
        public ?string $subject = null,
        public ?TaskAssignee $createdBy = null,
        public ?TaskAssignee $assignedTo = null,
        public ?array $assignees = null,
        public ?bool $assignedToCustomer = null,
        public ?TaskAssignee $completedBy = null,
        public ?TaskAssociated $associated = null,
        public ?bool $isBillable = null,
        public ?bool $showOnWorkOrder = null,
        public ?bool $emailNotifications = null,
        public ?string $description = null,
        public ?string $startDate = null,
        public ?string $dueDate = null,
        public ?string $completedDate = null,
        public ?string $notes = null,
        public ?string $status = null,
        public ?string $priority = null,
        public ?Reference $category = null,
        public ?TaskDuration $estimated = null,
        public ?TaskDuration $actual = null,
        public ?Reference $parentTask = null,
        public ?array $subTasks = null,
        public ?array $customFields = null,
        public ?string $percentComplete = null,
        public ?DateTimeImmutable $dateModified = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            subject: $data['Subject'] ?? null,
            createdBy: isset($data['CreatedBy']) && is_array($data['CreatedBy'])
                ? TaskAssignee::fromArray($data['CreatedBy'])
                : null,
            assignedTo: isset($data['AssignedTo']) && is_array($data['AssignedTo'])
                ? TaskAssignee::fromArray($data['AssignedTo'])
                : null,
            assignees: isset($data['Assignees']) && is_array($data['Assignees'])
                ? array_map(fn (array $item) => TaskAssignee::fromArray($item), $data['Assignees'])
                : null,
            assignedToCustomer: $data['AssignedToCustomer'] ?? null,
            completedBy: isset($data['CompletedBy']) && is_array($data['CompletedBy'])
                ? TaskAssignee::fromArray($data['CompletedBy'])
                : null,
            associated: isset($data['Associated']) && is_array($data['Associated'])
                ? TaskAssociated::fromArray($data['Associated'])
                : null,
            isBillable: $data['IsBillable'] ?? null,
            showOnWorkOrder: $data['ShowOnWorkOrder'] ?? null,
            emailNotifications: $data['EmailNotifications'] ?? null,
            description: $data['Description'] ?? null,
            startDate: $data['StartDate'] ?? null,
            dueDate: $data['DueDate'] ?? null,
            completedDate: $data['CompletedDate'] ?? null,
            notes: $data['Notes'] ?? null,
            status: $data['Status'] ?? null,
            priority: $data['Priority'] ?? null,
            category: isset($data['Category']) && is_array($data['Category']) && ! empty($data['Category'])
                ? Reference::fromArray($data['Category'])
                : null,
            estimated: isset($data['Estimated']) && is_array($data['Estimated'])
                ? TaskDuration::fromArray($data['Estimated'])
                : null,
            actual: isset($data['Actual']) && is_array($data['Actual'])
                ? TaskDuration::fromArray($data['Actual'])
                : null,
            parentTask: isset($data['ParentTask']) && is_array($data['ParentTask']) && ! empty($data['ParentTask'])
                ? Reference::fromArray($data['ParentTask'])
                : null,
            subTasks: $data['SubTasks'] ?? null,
            customFields: isset($data['CustomFields']) && is_array($data['CustomFields'])
                ? array_map(fn (array $item) => CustomField::fromArray($item), $data['CustomFields'])
                : null,
            percentComplete: $data['PercentComplete'] !== '' ? ($data['PercentComplete'] ?? null) : null,
            dateModified: isset($data['DateModified'])
                ? new DateTimeImmutable($data['DateModified'])
                : null,
        );
    }
}
