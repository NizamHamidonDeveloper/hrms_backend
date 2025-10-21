<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaseModel extends Model
{
    /**
     * Automatically convert all empty strings ("") to null and trim strings.
     */
    public function setAttribute($key, $value)
    {
        if (is_string($value)) {
            $value = trim($value);
            if ($value === '') {
                $value = null;
            }
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Handle encoding of JSON fields where [] should become null.
     */
    protected function setJsonField($key, $value)
    {
        if (empty($value)) {
            $this->attributes[$key] = null;
        } else {
            $this->attributes[$key] = json_encode($value, JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Handle decoding of JSON fields where null becomes [].
     */
    protected function getJsonField($value)
    {
        if (empty($value)) return [];
        $decoded = json_decode($value, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : [];
    }
}
