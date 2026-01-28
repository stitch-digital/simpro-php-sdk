<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\Attachments;

use DateTimeImmutable;
use Saloon\Http\Response;

final readonly class AttachmentFile
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $filename,
        public ?string $mimeType,
        public ?int $size,
        public ?string $url,
        public ?int $folderId,
        public ?DateTimeImmutable $dateCreated,
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
            name: $data['Name'] ?? null,
            filename: $data['Filename'] ?? null,
            mimeType: $data['MimeType'] ?? null,
            size: isset($data['Size']) ? (int) $data['Size'] : null,
            url: $data['URL'] ?? null,
            folderId: isset($data['Folder']['ID']) ? (int) $data['Folder']['ID'] : null,
            dateCreated: isset($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
            dateModified: isset($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
        );
    }
}
