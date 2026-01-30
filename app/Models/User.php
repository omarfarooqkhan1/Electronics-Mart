<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'email_verified_at',
        'email_verification_code',
        'email_verification_code_created_at',
        'password_reset_code',
        'registration_date',
        'is_admin_notified',
    ];

    /**
     * Check if user is email-verified
     */
    public function isEmailVerified(): bool
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Check if user needs to verify email
     */
    public function needsEmailVerification(): bool
    {
        return is_null($this->email_verified_at) && !empty($this->email_verification_code);
    }

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verification_code',
        'password_reset_code',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'email_verification_code_created_at' => 'datetime',
            'registration_date' => 'datetime',
            'password' => 'hashed',
            'is_admin_notified' => 'boolean',
        ];
    }

    /**
     * Check if user has admin role
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user has customer role
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Check if user is the super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->email === 'admin@electronicsmart.com';
    }

    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the addresses for the user.
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Scope to get only admin users
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope to get customer users
     */
    public function scopeCustomers($query)
    {
        return $query->where('role', 'customer');
    }
}