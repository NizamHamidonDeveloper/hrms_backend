<?php

namespace App\Services;

use App\Enum\DataStatus;
use Illuminate\Support\Facades\Auth;

abstract class BaseService
{
    protected $message = "";
    protected function createdBy(): array
    {
        return [
            'status' => DataStatus::Active,
            'created_by' => Auth::id(),
        ];
    }

    protected function updatedBy(): array
    {
        return [
            'updated_by' => Auth::id(),
        ];
    }

    protected function markDeleted($model)
    {
        $model->update([
            'status' => DataStatus::Deleted,
            'updated_by' => Auth::id(),
        ]);
    }

    protected function merge(array ...$arrays): array
    {
        return array_merge(...$arrays);
    }
}
