<?php

namespace App\Enums;

enum IssueStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in progress';
    case Dropped = 'dropped';
    case Fixed = 'fixed';

    public static function completedCases(): array
    {
        return [
            self::Dropped,
            self::Fixed
        ];
    }
}
