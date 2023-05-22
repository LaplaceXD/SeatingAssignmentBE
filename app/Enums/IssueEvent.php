<?php

namespace App\Enums;

enum IssueEvent: string
{
    case Raised = 'raised';
    case Validated = 'validated';
    case DetailsUpdated = 'detailsUpdated';
    case Assigned = 'assigned';
    case StatusChanged = 'statusChanged';
}
