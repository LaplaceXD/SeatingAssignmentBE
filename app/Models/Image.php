<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function issue(): BelongsTo
    {
        return $this->belongsTo(Issue::class, 'IssueID', 'IssueID');
    }
}
