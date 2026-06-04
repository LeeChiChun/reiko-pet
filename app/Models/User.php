<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'role', 'store_id', 'password',
        'login_failed_count', 'login_locked_until',
        'email_verified_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at'  => 'datetime',
            'password'           => 'hashed',
            'role'               => UserRole::class,
            'login_locked_until' => 'datetime',
        ];
    }

    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }

    public function isLockedOut(): bool
    {
        return $this->login_locked_until && now()->lt($this->login_locked_until);
    }

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'customer_id');
    }

    public function groomingSchedule()
    {
        return $this->hasMany(Appointment::class, 'groomer_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function articleBookmarks()
    {
        return $this->hasMany(ArticleBookmark::class);
    }

    public function productBookmarks()
    {
        return $this->hasMany(ProductBookmark::class);
    }

    public function isCustomer(): bool
    {
        return $this->role === UserRole::Customer;
    }

    public function isGroomer(): bool
    {
        return $this->role === UserRole::Groomer;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }
}
