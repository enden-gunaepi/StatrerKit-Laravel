<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_active', 'profile_picture', 'phone_code', 'phone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke Roles


    // Cek apakah user memiliki permission tertentu
    public function hasPermission($permission)
    {
        return $this->roles->flatMap(function ($role) {
            return $role->permissions;
        })->contains('name', $permission);
    }

    // Cek apakah user memiliki role tertentu
    public function hasRole($role)
    {
        return $this->roles->contains('name', $role);
    }
    // Mendapatkan semua permissions melalui role


    // app/Models/User.php
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }


    public function permissions()
    {
        return $this->roles->flatMap->permissions->unique();
    }


    public function allPermissions()
    {
        return $this->roles->flatMap(function ($role) {
            return $role->permissions;
        })->unique();
    }
}
