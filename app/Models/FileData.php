<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileData extends Model
{
    protected $table = 'file_data';

    protected $fillable = [
        'email',
        'image',
    ];

    public $incrementing = false; // because id is string
    protected $keyType = 'string';
}
