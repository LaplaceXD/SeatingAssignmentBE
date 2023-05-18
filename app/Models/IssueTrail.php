<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Enums\TrailActionType;

class IssueTrail extends Model
{
    use HasFactory;

    protected $primaryKey = 'TrailID';

    protected $casts = [
        'ActionType' => TrailActionType::class
    ];
}
