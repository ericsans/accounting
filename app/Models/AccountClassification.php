<?php

namespace App\Models;

use App\Enums\AccountClassificationNormalBalance;
use App\Enums\AccountClassificationRootType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'code',
    'name',
    'description',
    'root_type',
    'normal_balance',
])]
class AccountClassification extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'root_type' => AccountClassificationRootType::class,
            'normal_balance' => AccountClassificationNormalBalance::class,
        ];
    }

    /**
     * Get related records of AccountSubClassifications
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accountSubClassifications(): HasMany
    {
        return $this->hasMany(AccountSubClassification::class);
    }

    /**
     * Get related records of Accounts
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }
}
