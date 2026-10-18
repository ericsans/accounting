<?php

namespace Database\Seeders;

use App\Models\AccountSubClassification;
use Illuminate\Database\Seeder;

class AccountSubClassificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $template = [
            'account_classification_id' => '',
            'cash_flow_classification_id' => '',
            'code' => '',
            'name' => '',
            'description' => '',
        ];

        $data = [
            [...$template, 'account_classification_id' => 1, 'cash_flow_classification_id' => 1, 'code' => '1100', 'name' => 'Cash'],
            [...$template, 'account_classification_id' => 1, 'cash_flow_classification_id' => 1, 'code' => '1200', 'name' => 'Bank'],
            [...$template, 'account_classification_id' => 1, 'cash_flow_classification_id' => 2, 'code' => '1300', 'name' => 'Receiveables'],
            [...$template, 'account_classification_id' => 1, 'cash_flow_classification_id' => 2, 'code' => '1400', 'name' => 'Inventories'],
            [...$template, 'account_classification_id' => 1, 'cash_flow_classification_id' => 3, 'code' => '1500', 'name' => 'Fixed Asset'],
            [...$template, 'account_classification_id' => 2, 'cash_flow_classification_id' => 2, 'code' => '2100', 'name' => 'Current Liabilities'],
            [...$template, 'account_classification_id' => 3, 'cash_flow_classification_id' => 4, 'code' => '3100', 'name' => 'Equity'],
            [...$template, 'account_classification_id' => 3, 'cash_flow_classification_id' => 4, 'code' => '3200', 'name' => 'Earning'],
            [...$template, 'account_classification_id' => 4, 'cash_flow_classification_id' => 2, 'code' => '4100', 'name' => 'Income'],
            [...$template, 'account_classification_id' => 5, 'cash_flow_classification_id' => 2, 'code' => '5100', 'name' => 'Cost of Goods Sold'],
            [...$template, 'account_classification_id' => 6, 'cash_flow_classification_id' => 2, 'code' => '6100', 'name' => 'Operating Expense'],
            [...$template, 'account_classification_id' => 7, 'cash_flow_classification_id' => 3, 'code' => '7100', 'name' => 'Non Operating Expense'],
            [...$template, 'account_classification_id' => 8, 'cash_flow_classification_id' => 2, 'code' => '8100', 'name' => 'Other Income'],
            [...$template, 'account_classification_id' => 9, 'cash_flow_classification_id' => 2, 'code' => '9100', 'name' => 'Other Expense'],
        ];

        foreach ($data as $item) {
            AccountSubClassification::updateOrCreate(
                ['code' => $item['code']],
                $item,
            );
        }
    }
}
