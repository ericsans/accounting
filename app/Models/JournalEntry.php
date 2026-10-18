<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'code',
    'journal_date',
    'description',
    'total_debit',
    'total_credit',
])]
class JournalEntry extends Model
{
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
