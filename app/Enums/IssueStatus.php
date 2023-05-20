<?php

namespace App\Enums;

enum IssueStatus: string
{
    case Raised = 'raised';
    case Validated = 'validated';
    case InProgress = 'in progress';
    case Dropped = 'dropped';
    case Fixed = 'fixed';
}
