<?php

namespace App\Traits;

use Carbon\Carbon;
use DateTime;

/**
 * helper to scope dates
 * @author hussenk@github.com
 */


trait DateTrait
{

    public function initializeDateTrait()
    {
        $this->casts = array_merge($this->casts, ['updated_at' => 'datetime:Y-m-d H:m:s', 'created_at' => 'datetime:Y-m-d H:m:s']);
    }


    public function scopeCreatedAtFrom($query, $date)
    {
        return $query->where('created_at', '>=', new DateTime($date));
    }

    public function scopeCreatedAtTo($query, $date)
    {
        return $query->where('created_at', '<=', new DateTime($date));
    }

    public function scopeUpdatedAtFrom($query, $date)
    {
        return $query->where('updated_at', '>=', new DateTime($date));
    }

    public function scopeUpdatedAtTo($query, $date)
    {
        return $query->where('updated_at', '<=', new DateTime($date));
    }
}
