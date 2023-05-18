<?php

namespace App\Enums;

enum UserType: string
{
    case Student = 'student';
    case Professor = 'professor';
    case Technician = 'technician';
}
