<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;


#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /* * @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /* *
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    public function beneficiaryProfile(): HasOne
    {
        return $this->hasOne(BeneficiaryProfile::class);
    }

    public function fairPriceShop(): HasOne
    {
        return $this->hasOne(FairPriceShop::class, 'owner_id');
    }
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function stockAllocationsCreated(): HasMany
    {
        return $this->hasMany(StockAllocation::class, 'allocated_by');
    }

    public function stockReceiptsRecorded(): HasMany
    {
        return $this->hasMany(StockReceipt::class, 'received_by');
    }
}
