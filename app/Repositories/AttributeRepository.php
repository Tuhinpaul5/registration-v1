<?php

namespace App\Repositories;

use App\Models\Attribute;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AttributeRepository
{
    public function getPaginatedData(int $perPage = 15): LengthAwarePaginator
    {
        return Attribute::paginate($perPage);
    }

    public function getAttribute(int $id): Attribute
    {
        return Attribute::findOrFail($id);
    } 
}