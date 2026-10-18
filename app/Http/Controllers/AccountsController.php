<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountsController extends Controller
{
    public function ledger(Request $request)
    {
        $filters = $request->validate([
            'account_id' => ['required', 'integer', 'exists:accounts,id'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $accountId = $filters['account_id'];
        $from = $filters['from'] ?? null;
        $to = $filters['to'] ?? null;

        $baseQuery = DB::table('journal_entry_details')
            ->join('journal_entries', 'journal_entries.id', '=', 'journal_entry_details.journal_entry_id')
            ->where('journal_entry_details.account_id', $accountId);

        // Calculate the opening balance before the requested period.
        $openingBalance = (clone $baseQuery)
            ->when($from, fn ($query) => $query->where('journal_entries.journal_date', '<', $from))
            ->when(!$from, fn ($query) => $query->whereRaw('1 = 0'))
            ->selectRaw('COALESCE(SUM(journal_entry_details.debit - journal_entry_details.credit), 0) as balance')
            ->value('balance');

        // Retrieve transactions within the requested period.
        $transactions = (clone $baseQuery)
            ->select([
                'journal_entries.journal_date',
                'journal_entries.code',
                'journal_entries.description as journal_description',
                'journal_entry_details.rownumber',
                'journal_entry_details.voucher',
                'journal_entry_details.description',
                'journal_entry_details.debit',
                'journal_entry_details.credit',
            ])
            ->when($from, fn ($query) => $query->whereDate('journal_entries.journal_date', '>=', $from))
            ->when($to, fn ($query) => $query->whereDate('journal_entries.journal_date', '<=', $to))
            ->orderBy('journal_entries.journal_date')
            ->orderBy('journal_entries.id')
            ->orderBy('journal_entry_details.rownumber')
            ->get();

        $balance = (float) $openingBalance;
        $totalDebit = 0;
        $totalCredit = 0;
        $transactions = $transactions->map(function ($transaction) use (&$balance, &$totalDebit, &$totalCredit) {
            $debit = (float) $transaction->debit;
            $credit = (float) $transaction->credit;

            $totalDebit += $debit;
            $totalCredit += $credit;
            $balance += $debit - $credit;

            $transaction->balance = round($balance, 2);

            return $transaction;
        });

        return response()->json([
            'account_id' => $accountId,
            'from' => $from,
            'to' => $to,
            'opening_balance' => round((float) $openingBalance, 2),
            'total_debit' => round($totalDebit, 2),
            'total_credit' => round($totalCredit, 2),
            'closing_balance' => round($balance, 2),
            'transactions' => $transactions,
        ]);
    }
}
