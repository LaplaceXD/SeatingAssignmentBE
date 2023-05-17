<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $primaryKey = 'ImageID';

    protected $fillable = [
        'IssueID',
        'ImageBinary',
        'FileName',
        'Extension'
    ];
}
