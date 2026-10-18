<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'account_classification_id',
    'cash_flow_classification_id',
    'code',
    'name',
    'description',
])]
class AccountSubClassification extends Model
{
    /**
     * Get related records of AccountClassification
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accountClassification(): BelongsTo
    {
        return $this->belongsTo(AccountClassification::class);
    }

    /**
     * Get related records of CashFlowClassification
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cashFlowClassification(): BelongsTo
    {
        return $this->belongsTo(CashFlowClassification::class);
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
