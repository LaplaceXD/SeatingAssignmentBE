<?php

namespace App\Models;

use App\Enums\IssueStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Enums\TrailActionType;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Auth;

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

    protected $visible = [
        'TrailID',
        'IssueID',
        'msg',
        'executor_name',
        'ExecutedAt'
    ];

    protected $appends = ['msg', 'executor_name'];

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

    protected function msg(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->FieldName) return $this->Message;

                switch ($this->FieldName) {
                    case 'Description':
                        return 'Changed issue description.';
                    case 'ReplicationSteps':
                        return 'Updated replication steps.';
                    case 'SeatNo':
                        return 'Changed seat number from \'' . $this->PreviousValue . '\' to \'' . $this->NewValue . '\'.';
                    case 'Status':
                        return 'Issue ' . (in_array(IssueStatus::from($this->NewValue), IssueStatus::completedCases()) ? 'was' : 'is') . ' ' . strtolower($this->NewValue) . '.';
                    case 'LabID':
                        $previous = Laboratory::find($this->PreviousValue);
                        $new = Laboratory::find($this->NewValue);

                        return 'Changed laboratory from \'' . $previous->labCode . '\' to \'' . $new->labCode . '\'.';
                    case 'TypeID':
                        $previous = IssueType::find($this->PreviousValue);
                        $new = IssueType::find($this->NewValue);

                        return 'Changed issue type from \'' . $previous->Name . '\' to \'' . $new->Name . '\'.';
                    case 'AssigneeID':
                        $new = User::find($this->NewValue);

                        return ($this->previousValue ? 'Reassigned' : 'Assigned') . ' to \'' . $new->LastName . '\'.';
                    default:
                        return $this->Message;
                }
            }
        );
    }

    protected function executorName(): Attribute
    {
        return Attribute::make(
            get: function () {
                $user = User::find($this->ExecutorID);
                return $user->FirstName . ' ' . $user->LastName;
            }
        );
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

        foreach (array_merge(Issue::$updatableFields, ['AssigneeID', 'Status']) as $field) {
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

    public function transform()
    {
        return [
            'TrailID' => $this->TrailID,
            'IssueID' => $this->IssueID,
            'Message' => $this->msg,
            'ExecutorName' => $this->executor_name,
            'ExecutedAt' => $this->ExecutedAt
        ];
    }
}
