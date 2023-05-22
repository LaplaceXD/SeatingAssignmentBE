<?php

namespace App\Enums;

enum UserRole: string
{
    case Student = 'student';
    case Professor = 'professor';
    case Technician = 'technician';

    public function level(): int
    {
        switch ($this) {
            case $this::Student:
                return 1;
            case $this::Professor:
            case $this::Technician:
                return 2;
        }
    }
}
