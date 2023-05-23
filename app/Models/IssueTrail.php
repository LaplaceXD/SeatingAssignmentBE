<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Enums\TrailActionType;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IssueTrail extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'IssueTrails';
    protected $primaryKey = 'TrailID';

    protected $fillable = [
        'IssueID',
        'ExecutorID',
        'FieldName',
        'PreviousValue',
        'NewValue',
        'Message',
        'ActionType'
    ];

    protected $casts = [
        'ActionType' => TrailActionType::class
    ];

    public function issue(): BelongsTo
    {
        return $this->belongsTo(Issue::class, 'IssueID', 'IssueID');
    }

    public function executor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ExecutorID', 'IssueID');
    }
}
