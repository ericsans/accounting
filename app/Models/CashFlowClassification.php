<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'code',
    'name',
    'description',
])]
class CashFlowClassification extends Model
{
    /**
     * Get related records of AccountSubClassifications
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accountSubClassifications(): HasMany
    {
        return $this->hasMany(AccountSubClassification::class);
    }
}
