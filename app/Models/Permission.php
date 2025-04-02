<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    // Define the fillable property to allow mass assignment for 'name' attribute
    protected $fillable = ['name'];

    // Relasi ke User
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role');
    }
}
