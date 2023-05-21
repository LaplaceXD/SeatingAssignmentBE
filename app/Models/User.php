<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Enums\UserType;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;
    protected $primaryKey = 'UserID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'FirstName',
        'LastName',
        'Email',
        'Password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'Password',
        'IsActive'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'Password' => 'hashed',
        'Role' => UserType::class
    ];

    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class, 'IssuerID');
    }

    public function validatedIssues(): HasMany
    {
        return $this->hasMany(Issue::class, 'ValidatorID');
    }

    public function assignedIssues(): HasMany
    {
        return $this->hasMany(Issue::class, 'AssigneeID');
    }

    protected function isAdmin(): Attribute
    {
        return Attribute::make(
            get: fn () => in_array($this->Role, [UserType::Professor, UserType::Technician])
        );
    }

    protected function roleLevel(): Attribute
    {
        return Attribute::make(
            get: fn () => UserType::getLevel($this->Role)
        );
    }
}
