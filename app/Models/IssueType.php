<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IssueType extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'IssueTypes';
    protected $primaryKey = 'TypeID';

    protected $fillable = [
        'Name'
    ];

    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class, 'TypeID');
    }
}
