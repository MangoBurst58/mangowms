<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'company_id', 'warehouse_id', 'phone', 'position', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

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
            'is_active' => 'boolean',
        ];
    }
    
    // Relasi ke Company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    // Relasi ke Warehouse
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    
    // Relasi ke Transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }
    
    // Relasi ke Approved Transactions
    public function approvedTransactions()
    {
        return $this->hasMany(Transaction::class, 'approved_by');
    }
}