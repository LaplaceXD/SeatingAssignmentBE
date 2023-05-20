<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
