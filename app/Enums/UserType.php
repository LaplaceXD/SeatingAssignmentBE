<?php

namespace App\Enums;

enum UserType: string
{
    case Student = 'Student';
    case Professor = 'Professor';
    case Technician = 'Technician';
}
