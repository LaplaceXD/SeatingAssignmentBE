<?php

namespace App\Enums;

enum IssueStatus: string
{
    case Raised = 'raised';
    case Validated = 'validated';
    case Pending = 'pending';
    case InProgress = 'in progress';
    case Dropped = 'dropped';
    case Fixed = 'fixed';

    public static function postValidationCases(): array
    {
        return [
            self::Pending,
            self::InProgress,
            self::Dropped,
            self::Fixed
        ];
    }

    public static function completedCases(): array
    {
        return [
            self::Dropped,
            self::Fixed
        ];
    }
}
