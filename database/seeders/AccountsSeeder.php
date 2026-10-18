<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class AccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $template = [
            'account_classification_id' => '',
            'account_sub_classification_id' => '',
            'code' => '',
            'name' => '',
            'description' => '',
            'is_cash_bank_account' => 0,
        ];

        $data = [
            [...$template, 'account_classification_id' => 1, 'account_sub_classification_id' => 1, 'code' => '1101', 'name' => 'Petty Cash', 'is_cash_bank_account' => 1],
            [...$template, 'account_classification_id' => 1, 'account_sub_classification_id' => 2, 'code' => '1201', 'name' => 'Bank', 'is_cash_bank_account' => 1],
            [...$template, 'account_classification_id' => 1, 'account_sub_classification_id' => 4, 'code' => '1301', 'name' => 'Account Receiveable'],
            [...$template, 'account_classification_id' => 1, 'account_sub_classification_id' => 4, 'code' => '1401', 'name' => 'Inventory'],
            [...$template, 'account_classification_id' => 1, 'account_sub_classification_id' => 5, 'code' => '1501', 'name' => 'Property'],
            [...$template, 'account_classification_id' => 2, 'account_sub_classification_id' => 6, 'code' => '2101', 'name' => 'Account Payable'],
            [...$template, 'account_classification_id' => 2, 'account_sub_classification_id' => 6, 'code' => '2102', 'name' => 'Tax Payable'],
            [...$template, 'account_classification_id' => 3, 'account_sub_classification_id' => 7, 'code' => '3101', 'name' => 'Owner Capital'],
            [...$template, 'account_classification_id' => 3, 'account_sub_classification_id' => 8, 'code' => '3201', 'name' => 'Current Year Earning'],
            [...$template, 'account_classification_id' => 4, 'account_sub_classification_id' => 9, 'code' => '4101', 'name' => 'Food Sales'],
            [...$template, 'account_classification_id' => 4, 'account_sub_classification_id' => 9, 'code' => '4102', 'name' => 'Beverage Sales'],
            [...$template, 'account_classification_id' => 4, 'account_sub_classification_id' => 9, 'code' => '4103', 'name' => 'Service Charge'],
            [...$template, 'account_classification_id' => 5, 'account_sub_classification_id' => 10, 'code' => '5101', 'name' => 'Cost of Food Sales'],
            [...$template, 'account_classification_id' => 5, 'account_sub_classification_id' => 10, 'code' => '5102', 'name' => 'Cost of Beverage Sales'],
            [...$template, 'account_classification_id' => 6, 'account_sub_classification_id' => 11, 'code' => '6101', 'name' => 'Sales Discount'],
            [...$template, 'account_classification_id' => 6, 'account_sub_classification_id' => 11, 'code' => '6102', 'name' => 'Purchase Discount'],
            [...$template, 'account_classification_id' => 6, 'account_sub_classification_id' => 11, 'code' => '6201', 'name' => 'Rent Expense'],
            [...$template, 'account_classification_id' => 7, 'account_sub_classification_id' => 12, 'code' => '7101', 'name' => 'Property Depreciation'],
            [...$template, 'account_classification_id' => 8, 'account_sub_classification_id' => 13, 'code' => '8101', 'name' => 'Interest Income'],
            [...$template, 'account_classification_id' => 9, 'account_sub_classification_id' => 14, 'code' => '9100', 'name' => 'Miscellaneous Expense'],
        ];

        foreach ($data as $item) {
            Account::updateOrCreate(
                ['code' => $item['code']],
                $item,
            );
        }
    }
}
