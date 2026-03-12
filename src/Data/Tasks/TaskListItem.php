<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Tasks;

final readonly class TaskListItem
{
    /**
     * @param  array<TaskAssignee>|null  $assignees
     */
    public function __construct(
        public int $id,
        public ?string $subject = null,
        public ?TaskAssignee $assignedTo = null,
        public ?array $assignees = null,
        public ?TaskAssignee $completedBy = null,
        public ?string $dueDate = null,
        public ?string $percentComplete = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            subject: $data['Subject'] ?? null,
            assignedTo: isset($data['AssignedTo']) && is_array($data['AssignedTo'])
                ? TaskAssignee::fromArray($data['AssignedTo'])
                : null,
            assignees: isset($data['Assignees']) && is_array($data['Assignees'])
                ? array_map(fn (array $item) => TaskAssignee::fromArray($item), $data['Assignees'])
                : null,
            completedBy: isset($data['CompletedBy']) && is_array($data['CompletedBy'])
                ? TaskAssignee::fromArray($data['CompletedBy'])
                : null,
            dueDate: $data['DueDate'] ?? null,
            percentComplete: $data['PercentComplete'] !== '' ? ($data['PercentComplete'] ?? null) : null,
        );
    }
}
