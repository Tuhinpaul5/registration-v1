<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    // Example: a scope to filter data
    public function scopeCustomFilter($query, string $criteria)
    {
        return $query->where('field', $criteria);
    }
}