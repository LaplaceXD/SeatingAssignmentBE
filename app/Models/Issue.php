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

class Issue extends Model
{
    use HasFactory;

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
            get: fn () => $this->ValidatedAt !== null
        );
    }

    protected function isFrozen(): Attribute
    {
        return Attribute::make(
            get: fn () => in_array($this->Status, [IssueStatus::Dropped, IssueStatus::Fixed])
        );
    }

    public function scopeOfStatus(Builder $query, ?IssueStatus $status): void
    {
        $query
            ->when($status, fn (Builder $query) => $query->where('Status', $status->value))
            ->when(
                in_array($status, IssueStatus::completedCases()),
                fn (Builder $query) => $query->orderByDesc('CompletedAt')
            )
            ->when(
                in_array($status, array_merge(IssueStatus::postValidationCases(), [IssueStatus::Validated])),
                fn (Builder $query) => $query->orderByDesc('ValidatedAt')
            )
            ->orderByDesc('IssuedAt');
    }

    public function scopeOfSeat(Builder $query, string $seat): void
    {
        $query->where('SeatNo', strtoupper($seat));
    }
}
