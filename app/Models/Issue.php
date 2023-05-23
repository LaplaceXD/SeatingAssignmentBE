<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

use App\Enums\IssueEvent;
use App\Enums\IssueStatus;
use App\Enums\UserRole;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use InvalidArgumentException;

class Issue extends Model
{
    use HasFactory;

    public static $updatableFields = ['LabID', 'TypeID', 'SeatNo', 'Description', 'ReplicationSteps'];

    public $timestamps = false;
    protected $primaryKey = 'IssueID';

    protected $fillable = [
        'IssuerID',
        'ValidatorID',
        'AssigneeID',
        'LabID',
        'TypeID',
        'SeatNo',
        'Description',
        'ReplicationSteps',
        'Status',
        'ValidatedAt',
        'CompletedAt'
    ];

    protected $casts = [
        'Status' => IssueStatus::class
    ];

    protected $dispatchesEvents = [
        IssueEvent::Raised->value => \App\Events\IssueRaised::class,
        IssueEvent::Validated->value => \App\Events\IssueValidated::class,
        IssueEvent::DetailsUpdated->value => \App\Events\IssueDetailsUpdated::class,
        IssueEvent::Assigned->value => \App\Events\IssueAssigned::class,
        IssueEvent::StatusChanged->value => \App\Events\IssueStatusChanged::class
    ];

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'IssuerID', 'UserID');
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ValidatorID', 'UserID');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'AssigneeID', 'UserID');
    }

    public function laboratory(): BelongsTo
    {
        return $this->belongsTo(Laboratory::class, 'LabID', 'LabID');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(IssueType::class, 'TypeID', 'TypeID')->withDefault(['Name' => 'Others']);
    }

    public function trails(): HasMany
    {
        return $this->hasMany(IssueTrail::class, 'IssueID');
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class, 'IssueID');
    }

    protected function seatNo(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => strtoupper($value)
        );
    }

    protected function isValidated(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->ValidatorID !== null
        );
    }

    protected function isCompleted(): Attribute
    {
        return Attribute::make(
            get: fn () => in_array($this->Status, IssueStatus::completedCases())
        );
    }

    public function scopeOfStatus(Builder $query, ?IssueStatus $status, bool $validatedOnly): void
    {
        $query
            ->when($validatedOnly, fn (Builder $query) => $query->whereNot('Status', null))
            ->when($status, fn (Builder $query) => $query->where('Status', $status->value))
            ->when(
                in_array($status, IssueStatus::completedCases()),
                fn (Builder $query) => $query->orderByDesc('CompletedAt')
            )
            ->when(
                in_array($status, IssueStatus::cases()) || $validatedOnly,
                fn (Builder $query) => $query->orderByDesc('ValidatedAt')
            )
            ->orderByDesc('IssuedAt');
    }

    public function scopeOfSeat(Builder $query, string $seat): void
    {
        $query->where('SeatNo', strtoupper($seat));
    }

    public static function raise(array $attributes)
    {
        $diff = array_diff(array_keys($attributes), self::$updatableFields);

        if (count($diff) !== 0) {
            throw new InvalidArgumentException(
                'Array must only contain the following keys: ' . implode(',', self::$updatableFields)
            );
        }

        $user = Auth::user();
        if (!$user || !$user->IsActive) throw new AuthenticationException('There is no user logged in.');

        $attributes['IssuerID'] = $user->UserID;

        $issue = self::create($attributes);
        $issue->fireModelEvent(IssueEvent::Raised->value);

        return $issue->fresh();
    }

    public function validate()
    {
        if ($this->isValidated) throw new Exception('This issue is already validated.');

        $user = Auth::user();
        if (!$user || !$user->IsActive) throw new AuthenticationException('There is no user logged in.');
        if (!$user->isAdmin) throw new UnauthorizedException('Current logged in user is not an admin.');

        $this->update([
            'ValidatorID' => $user->UserID,
            'ValidatedAt' => Carbon::now(),
            'Status' => IssueStatus::Pending
        ]);

        $this->fireModelEvent(IssueEvent::Validated->value);

        return $this;
    }

    public function setDetails(array $attributes)
    {
        $diff = array_diff(array_keys($attributes), self::$updatableFields);

        if (count($diff) !== 0) {
            throw new InvalidArgumentException(
                'Array must only contain the following keys: ' . implode(',', self::$updatableFields)
            );
        }

        $this->update($attributes);
        $this->fireModelEvent(IssueEvent::DetailsUpdated->value);

        return $this;
    }

    public function setAssignee(?User $user)
    {
        if (!$this->isValidated) throw new Exception('This issue is not yet validated.');
        if ($user && $user->Role !== UserRole::Technician) throw new Exception('The user provided is not valid.');

        $this->assignee()->associate($user);
        $this->fireModelEvent(IssueEvent::Assigned->value);

        return $this;
    }

    public function setStatus(IssueStatus $status)
    {
        if (in_array($status, IssueStatus::completedCases()) && !$this->isCompleted) {
            $this->CompletedAt = Carbon::now();
        } else if ($status !== IssueStatus::Dropped && $this->Status === IssueStatus::Dropped && !$this->isValidated) {
            $this->validate();
        }

        $this->Status = $status;
        $this->save();

        $this->fireModelEvent(IssueEvent::StatusChanged->value);
        return $this;
    }
}
