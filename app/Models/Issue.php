<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Enums\IssueStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
}
