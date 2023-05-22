<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laboratory extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'LabID';
    protected $hidden = ['BuildingCode', 'FloorNo', 'AisleNo', 'RoomNo'];
    protected $appends = ['lab_code', 'seats'];

    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class, 'LabID');
    }

    public function labCode(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->BuildingCode . $this->FloorNo . $this->AisleNo . $this->RoomNo
        );
    }

    public function seats(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->issues()->get('SeatNo')->map(fn (Issue $issue) => $issue->SeatNo)
        );
    }

    public function transform()
    {
        return [
            'LabID' => $this->LabID,
            'LabName' => $this->LabName,
            'LabCode' => $this->lab_code,
            'Capacity' => $this->Capacity,
            'Seats' => $this->seats
        ];
    }
}
