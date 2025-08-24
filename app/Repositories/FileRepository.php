<?php

namespace App\Repositories;

use App\Models\FileData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FileRepository
{
    public function get_file_by_email(string $email): ?FileData
    {
        return FileData::where('email', $email)->first();
    }
}