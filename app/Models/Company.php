<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'memberships')
            ->withPivot(['role', 'status'])
            ->withTimestamps();
    }

    /**
     * Users with an active membership in this company.
     */
    public function activeMembers()
    {
        return $this->belongsToMany(User::class, 'memberships')
            ->wherePivot('status', 'active')
            ->withPivot(['role', 'status'])
            ->withTimestamps();
    }
}
