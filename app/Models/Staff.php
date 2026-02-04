<?php

namespace App\Models;

use App\Models\Department;
use App\Models\StaffDocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'staff_id',
        'position',
        'photo',
        'department_id',
        'phone_number',
        'email',
        'status',
        'valid_until',
        'employment_type',
        'date_joined',
        'location',
        'security_clearance',
        'last_verified',
        'background_check',
    ];

    protected $casts = [
        'valid_until' => 'date',
        'date_joined' => 'date',
        'last_verified' => 'date',
        'background_check' => 'boolean',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)->withDefault([
            'name' => 'Default',
        ]);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(StaffDocument::class);
    }

    protected function fullname(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name,
        );
    }
}
