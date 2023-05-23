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

    public static function logInfo(Issue $issue, string $message)
    {
        $user = Auth::user();
        if (!$user) throw new AuthenticationException('There is no user logged in.');

        return IssueTrail::create([
            'IssueID' => $issue->IssueID,
            'ExecutorID' => $user->UserID,
            'Message' => $message,
            'ActionType' => TrailActionType::Message
        ]);
    }

    public static function logChange(Issue $issue)
    {
        $user = Auth::user();
        if (!$user) throw new AuthenticationException('There is no user logged in.');

        $baseState = [
            'IssueID' => $issue->IssueID,
            'ExecutorID' => $user->UserID,
            'ActionType' => TrailActionType::Change
        ];

        foreach (array_merge(Issue::$updatableFields, ['AssigneeID']) as $field) {
            if ($issue->isDirty($field)) {
                IssueTrail::create(array_merge($baseState, [
                    'FieldName' => $field,
                    'PreviousValue' => $issue->getOriginal($field),
                    'NewValue' => $issue->getAttribute($field)
                ]));
            }
        }

        return IssueTrail::all();
    }
}
