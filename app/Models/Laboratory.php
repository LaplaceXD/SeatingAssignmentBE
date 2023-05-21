<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laboratory extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'LabID';

    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class, 'LabID');
    }
}
