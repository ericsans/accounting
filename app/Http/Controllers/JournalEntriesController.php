<?php

namespace App\Http\Controllers;

use App\Http\Requests\JournalEntryRequest;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\DB;

class JournalEntriesController extends Controller
{
    public function store(JournalEntryRequest $request)
    {
        $data = $request->validated();
        return DB::transaction(function () use ($data) {
            $details = $data['journal_entry_details'];

            $data['total_debit'] = round(array_sum(array_column($details, 'debit')), 2);
            $data['total_credit'] = round(array_sum(array_column($details, 'credit')), 2);
            unset($data['journal_entry_details']);

            $journal = JournalEntry::create($data);
            $journal->journalEntryDetails()->createMany(
                array_map(
                    static fn (array $detail, int $index): array => [
                        ...$detail,
                        'rownumber' => $index + 1,
                    ],
                    $details,
                    array_keys($details),
                ),
            );

            return $journal->load('journalEntryDetails');
        });
    }

    public function update(JournalEntryRequest $request, JournalEntry $journalEntry) {
        $data = $request->validated();
        return DB::transaction(function () use ($data, $journalEntry) {
            $details = $data['journal_entry_details'];

            $data['total_debit'] = round(array_sum(array_column($details, 'debit')), 2);
            $data['total_credit'] = round(array_sum(array_column($details, 'credit')), 2);
            unset($data['journal_entry_details']);

            $journalEntry->update($data);

            $journalEntry->journalEntryDetails()->delete();
            $journalEntry->journalEntryDetails()->createMany(
                array_map(
                    static fn (array $detail, int $index): array => [
                        ...$detail,
                        'rownumber' => $index + 1,
                    ],
                    $details,
                    array_keys($details),
                ),
            );

            return $journalEntry->load('journalEntryDetails');
        });
    }

    public function destroy(JournalEntry $journalEntry)
    {
        DB::transaction(function () use ($journalEntry) {
            $journalEntry->journalEntryDetails()->delete();
            $journalEntry->delete();
        });

        return response()->noContent();
    }
}
