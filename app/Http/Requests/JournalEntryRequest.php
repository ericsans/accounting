<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class JournalEntryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                Rule::unique('journal_entries', 'code')->ignore($this->route('journalEntry')?->id),
            ],
            'journal_date' => [
                'required',
                'date',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'journal_entry_details' => [
                'required',
                'array',
                'min:1',
            ],
            'journal_entry_details.*.account_id' => [
                'required',
                'integer',
                'exists:accounts,id',
            ],
            'journal_entry_details.*.voucher' => [
                'nullable',
                'string',
            ],
            'journal_entry_details.*.description' => [
                'nullable',
                'string',
            ],
            'journal_entry_details.*.debit' => [
                'required',
                'numeric',
                'min:0',
            ],
            'journal_entry_details.*.credit' => [
                'required',
                'numeric',
                'min:0',
            ],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $details = $this->input('journal_entry_details', []);

                $totalDebit = 0;
                $totalCredit = 0;

                foreach ($details as $index => $detail) {
                    $debit = (float) $detail['debit'];
                    $credit = (float) $detail['credit'];

                    if ($debit == 0 && $credit == 0 || $debit > 0 && $credit > 0) {
                        $validator->errors()->add("journal_entry_details.$index.debit", 'Each detail must have either a debit or a credit amount, but not both');
                        continue;
                    }

                    $totalDebit += $debit;
                    $totalCredit += $credit;
                }

                $totalDebit = round($totalDebit, 2);
                $totalCredit = round($totalCredit, 2);

                if ($totalDebit != $totalCredit) {
                    $validator->errors()->add('journal_entry_details', 'The journal entry is not balanced');
                }
            },
        ];
    }
}
