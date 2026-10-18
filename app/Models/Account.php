<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'account_classification_id',
    'account_sub_classification_id',
    'code',
    'name',
    'description',
])]
class Account extends Model
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
     * Get related records of AccountSubClassification
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accountSubClassification(): BelongsTo
    {
        return $this->belongsTo(AccountSubClassification::class);
    }

    /**
     * Get related records of JournalEntryDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function journalEntryDetails(): HasMany
    {
        return $this->hasMany(JournalEntryDetail::class);
    }
}
