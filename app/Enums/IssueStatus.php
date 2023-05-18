<?php

namespace App\Enums;

enum IssueStatus: string
{
    case Raised = 'raised';
    case Validatated = 'validated';
    case InProgress = 'in progress';
    case Dropped = 'dropped';
    case Fixed = 'fixed';
}
