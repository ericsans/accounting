<?php

namespace Database\Seeders;

use App\Models\CashFlowClassification;
use Illuminate\Database\Seeder;

class CashFlowClassificationsSeeder extends Seeder
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
        ];

        $data = [
            [...$template, 'code' => '001', 'name' => 'Undefined'],
            [...$template, 'code' => '002', 'name' => 'Operating Activity'],
            [...$template, 'code' => '003', 'name' => 'Investing Activity'],
            [...$template, 'code' => '004', 'name' => 'Financing Activity'],
        ];

        foreach ($data as $item) {
            CashFlowClassification::updateOrCreate(
                ['code' => $item['code']],
                $item,
            );
        }
    }
}
