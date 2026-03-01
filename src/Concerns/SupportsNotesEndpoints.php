<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\NoteResource;

trait SupportsNotesEndpoints
{
    public function notes(int $companyId = 0): NoteResource
    {
        return new NoteResource($this, $companyId);
    }
}
