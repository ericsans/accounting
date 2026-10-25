<?php

namespace App\Http\Controllers;

use App\Enums\AccountClassificationNormalBalance;
use App\Enums\AccountClassificationRootType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialReportsController extends Controller
{
    public function balanceSheet(Request $request)
    {
        $filters = $request->validate([
            'as_of' => ['required', 'date'],
        ]);

        $asOf = $filters['as_of'];
        $accounts = DB::table('accounts')
            ->join('account_classifications', 'account_classifications.id', '=', 'accounts.account_classification_id')
            ->leftJoin('journal_entry_details', 'journal_entry_details.account_id', '=', 'accounts.id')
            ->leftJoin(
                'journal_entries',
                fn ($join) =>
                    $join->on('journal_entries.id', '=', 'journal_entry_details.journal_entry_id')
                         ->whereDate('journal_entries.journal_date', '<=', $asOf)
            )
            ->select([
                'accounts.id',
                'accounts.code',
                'accounts.name',
                'account_classifications.root_type',
                'account_classifications.normal_balance',
            ])
            ->selectRaw('COALESCE(SUM(CASE WHEN journal_entries.id IS NOT NULL THEN journal_entry_details.debit ELSE 0 END), 0) as total_debit')
            ->selectRaw('COALESCE(SUM(CASE WHEN journal_entries.id IS NOT NULL THEN journal_entry_details.credit ELSE 0 END), 0) as total_credit')
            ->groupBy('accounts.id', 'accounts.code', 'accounts.name', 'account_classifications.root_type')
            ->orderBy('accounts.code')
            ->get()
            ->map(function ($account) {
                $debit = (float) $account->total_debit;
                $credit = (float) $account->total_credit;

                $account->balance = round(
                    $account->normal_balance === AccountClassificationNormalBalance::Debit->value
                        ? $debit - $credit
                        : $credit - $debit,
                    2
                );

                return $account;
            });

        $mapAccount = fn ($account) => [
            'account_id' => $account->id,
            'account_code' => $account->code,
            'account_name' => $account->name,
            'balance' => $account->balance,
        ];
        $assetAccounts = $accounts->where('root_type', AccountClassificationRootType::Assets->value)->map($mapAccount)->values();
        $liabilityAccounts = $accounts->where('root_type', AccountClassificationRootType::Liabilities->value)->map($mapAccount)->values();
        $equityAccounts = $accounts->where('root_type', AccountClassificationRootType::Equity->value)->map($mapAccount)->values();

        $totalAssets = round($assetAccounts->sum('balance'), 2);
        $totalLiabilities = round($liabilityAccounts->sum('balance'), 2);
        $totalEquity = round($equityAccounts->sum('balance'), 2);
        $totalRevenue = round($accounts->where('root_type', AccountClassificationRootType::Revenue->value)->sum('balance'), 2);
        $totalExpenses = round($accounts->where('root_type', AccountClassificationRootType::Expenses->value)->sum('balance'), 2);
        $netIncome = round($totalRevenue - $totalExpenses, 2);
        $totalEquityWithNetIncome = round($totalEquity + $netIncome, 2);

        return response()->json([
            'as_of' => $asOf,
            'assets' => [
                'accounts' => $assetAccounts,
                'total' => $totalAssets,
            ],
            'liabilities' => [
                'accounts' => $liabilityAccounts,
                'total' => $totalLiabilities,
            ],
            'equity' => [
                'accounts' => $equityAccounts,
                'net_income' => $netIncome,
                'total' => $totalEquityWithNetIncome,
            ],
        ]);
    }

    public function incomeStatement(Request $request)
    {
        $filters = $request->validate([
            'from' => ['required', 'date'],
            'to' => ['required', 'date', 'after_or_equal:from'],
        ]);

        $from = $filters['from'];
        $to = $filters['to'];

        $accounts = DB::table('accounts')
            ->join('account_classifications', 'account_classifications.id', '=', 'accounts.account_classification_id')
            ->leftJoin('journal_entry_details', 'journal_entry_details.account_id', '=', 'accounts.id')
            ->leftJoin('journal_entries', function ($join) use ($from, $to) {
                $join->on('journal_entries.id', '=', 'journal_entry_details.journal_entry_id')
                    ->whereDate('journal_entries.journal_date', '>=', $from)
                    ->whereDate('journal_entries.journal_date', '<=', $to);
            })
            ->whereIn('account_classifications.root_type', [
                AccountClassificationRootType::Revenue->value,
                AccountClassificationRootType::Expenses->value,
            ])
            ->select([
                'accounts.id',
                'accounts.code',
                'accounts.name',
                'account_classifications.root_type',
                'account_classifications.normal_balance',
            ])
            ->selectRaw('COALESCE(SUM(CASE WHEN journal_entries.id IS NOT NULL THEN journal_entry_details.debit ELSE 0 END), 0) AS total_debit')
            ->selectRaw('COALESCE(SUM(CASE WHEN journal_entries.id IS NOT NULL THEN journal_entry_details.credit ELSE 0 END), 0) AS total_credit')
            ->groupBy(
                'accounts.id',
                'accounts.code',
                'accounts.name',
                'account_classifications.root_type',
                'account_classifications.normal_balance'
            )
            ->orderBy('accounts.code')
            ->get()
            ->map(function ($account) {
                $debit = (float) $account->total_debit;
                $credit = (float) $account->total_credit;

                $account->total_debit = round($debit, 2);
                $account->total_credit = round($credit, 2);

                $account->balance = round(
                    $account->normal_balance === AccountClassificationNormalBalance::Debit->value
                        ? $debit - $credit
                        : $credit - $debit,
                    2
                );

                return $account;
            });

        $mapAccount = fn ($account) => [
            'account_id' => $account->id,
            'account_code' => $account->code,
            'account_name' => $account->name,
            'balance' => $account->balance,
        ];

        $revenueAccounts = $accounts
            ->where('root_type', AccountClassificationRootType::Revenue->value)
            ->filter(fn ($account) => $account->balance != 0)
            ->map($mapAccount)
            ->values();

        $expenseAccounts = $accounts
            ->where('root_type', AccountClassificationRootType::Expenses->value)
            ->filter(fn ($account) => $account->balance != 0)
            ->map($mapAccount)
            ->values();

        $totalRevenue = round($revenueAccounts->sum('balance'), 2);
        $totalExpenses = round($expenseAccounts->sum('balance'), 2);
        $netIncome = round($totalRevenue - $totalExpenses, 2);

        return response()->json([
            'from' => $from,
            'to' => $to,
            'revenue' => [
                'accounts' => $revenueAccounts,
                'total' => $totalRevenue,
            ],
            'expenses' => [
                'accounts' => $expenseAccounts,
                'total' => $totalExpenses,
            ],
            'net_income' => $netIncome,
        ]);
    }
}
