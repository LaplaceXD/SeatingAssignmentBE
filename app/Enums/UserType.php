<?php

namespace App\Enums;

enum UserType: string
{
    case Student = 'student';
    case Professor = 'professor';
    case Technician = 'technician';

    public static function getLevel(UserType $type): int
    {
        switch ($type) {
            case self::Student:
                return 1;
            case self::Professor:
                return 2;
            case self::Technician:
                return 2;
        }
    }
}
