<?php

namespace Database\Seeders;

use App\Enums\AccountClassificationNormalBalance;
use App\Enums\AccountClassificationRootType;
use App\Models\AccountClassification;
use Illuminate\Database\Seeder;

class AccountClassificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $template = [
            'code' => '',
            'name' => '',
            'description' => '',
            'root_type' => '',
            'normal_balance' => '',
        ];

        $data = [
            [...$template, 'code' => '100', 'name' => 'Asset', 'root_type' => AccountClassificationRootType::Assets->value, 'normal_balance' => AccountClassificationNormalBalance::Debit->value],
            [...$template, 'code' => '200', 'name' => 'Liabilities', 'root_type' => AccountClassificationRootType::Liabilities->value, 'normal_balance' => AccountClassificationNormalBalance::Credit->value],
            [...$template, 'code' => '300', 'name' => 'Equity', 'root_type' => AccountClassificationRootType::Equity->value, 'normal_balance' => AccountClassificationNormalBalance::Credit->value],
            [...$template, 'code' => '400', 'name' => 'Revenue', 'root_type' => AccountClassificationRootType::Revenue->value, 'normal_balance' => AccountClassificationNormalBalance::Credit->value],
            [...$template, 'code' => '500', 'name' => 'Cost Of Sales', 'root_type' => AccountClassificationRootType::Expenses->value, 'normal_balance' => AccountClassificationNormalBalance::Debit->value],
            [...$template, 'code' => '600', 'name' => 'Operating Expense', 'root_type' => AccountClassificationRootType::Expenses->value, 'normal_balance' => AccountClassificationNormalBalance::Debit->value],
            [...$template, 'code' => '700', 'name' => 'Non Operating Expense', 'root_type' => AccountClassificationRootType::Expenses->value, 'normal_balance' => AccountClassificationNormalBalance::Debit->value],
            [...$template, 'code' => '800', 'name' => 'Other Income', 'root_type' => AccountClassificationRootType::Revenue->value, 'normal_balance' => AccountClassificationNormalBalance::Credit->value],
            [...$template, 'code' => '900', 'name' => 'Other Expense', 'root_type' => AccountClassificationRootType::Expenses->value, 'normal_balance' => AccountClassificationNormalBalance::Debit->value],
        ];

        foreach ($data as $item) {
            AccountClassification::updateOrCreate(
                ['code' => $item['code']],
                $item,
            );
        }
    }
}
