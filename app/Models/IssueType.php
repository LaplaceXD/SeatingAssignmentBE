<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueType extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'IssueTypes';

    protected $fillable = [
        'Name'
    ];
}
