<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'journal_entry_id',
    'account_id',
    'rownumber',
    'voucher',
    'description',
    'debit',
    'credit',
])]
class JournalEntryDetail extends Model
{
    /**
     * Get related records of JournalEntry
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }

    /**
     * Get related records of Account
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
