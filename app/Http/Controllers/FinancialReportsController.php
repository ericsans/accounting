<?php

namespace App\Http\Controllers;

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
            ->leftJoin('journal_entries', function ($join) use ($asOf) {
                $join->on('journal_entries.id', '=', 'journal_entry_details.journal_entry_id')
                     ->whereDate('journal_entries.journal_date', '<=', $asOf);
            })
            ->select([
                'accounts.id',
                'accounts.code',
                'accounts.name',
                'account_classifications.root_type',
            ])
            ->selectRaw('COALESCE(SUM(CASE WHEN journal_entries.id IS NOT NULL THEN journal_entry_details.debit ELSE 0 END), 0) as total_debit')
            ->selectRaw('COALESCE(SUM(CASE WHEN journal_entries.id IS NOT NULL THEN journal_entry_details.credit ELSE 0 END), 0) as total_credit')
            ->groupBy('accounts.id', 'accounts.code', 'accounts.name', 'account_classifications.root_type')
            ->orderBy('accounts.code')
            ->get()
            ->map(function ($account) {
                $account->total_debit = round((float) $account->total_debit, 2);
                $account->total_credit = round((float) $account->total_credit, 2);
                $account->balance = round($account->total_debit - $account->total_credit, 2);

                return $account;
            });

        $assetsType = AccountClassificationRootType::Assets->value;
        $liabilitiesType = AccountClassificationRootType::Liabilities->value;
        $equityType = AccountClassificationRootType::Equity->value;
        $revenueType = AccountClassificationRootType::Revenue->value;
        $expensesType = AccountClassificationRootType::Expenses->value;

        $assets = $accounts
            ->where('root_type', $assetsType)
            ->map(fn ($account) => [
                'account_id' => $account->id,
                'account_code' => $account->code,
                'account_name' => $account->name,
                'balance' => $account->balance,
            ])
            ->values();

        $liabilities = $accounts
            ->where('root_type', $liabilitiesType)
            ->map(fn ($account) => [
                'account_id' => $account->id,
                'account_code' => $account->code,
                'account_name' => $account->name,
                'balance' => round(-$account->balance, 2),
            ])
            ->values();

        $equity = $accounts
            ->where('root_type', $equityType)
            ->map(fn ($account) => [
                'account_id' => $account->id,
                'account_code' => $account->code,
                'account_name' => $account->name,
                'balance' => round(-$account->balance, 2),
            ])
            ->values();

        $totalAssets = round($assets->sum('balance'), 2);
        $totalLiabilities = round($liabilities->sum('balance'), 2);
        $totalEquity = round($equity->sum('balance'), 2);
        $totalRevenue = round(-$accounts->where('root_type', $revenueType)->sum('balance'), 2);
        $totalExpenses = round($accounts->where('root_type', $expensesType)->sum('balance'), 2);
        $netIncome = round($totalRevenue - $totalExpenses, 2);
        $totalEquityWithNetIncome = round($totalEquity + $netIncome, 2);

        return response()->json([
            'as_of' => $asOf,
            'assets' => [
                'accounts' => $assets,
                'total' => $totalAssets,
            ],
            'liabilities' => [
                'accounts' => $liabilities,
                'total' => $totalLiabilities,
            ],
            'equity' => [
                'accounts' => $equity,
                'net_income' => $netIncome,
                'total' => $totalEquityWithNetIncome,
            ],
        ]);
    }
}
