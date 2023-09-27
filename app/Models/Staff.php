<?php

namespace App\Models;

use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)->withDefault([
            'name' => 'Default',
        ]);
    }

    protected function fullname(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name,
        );
    }
}
