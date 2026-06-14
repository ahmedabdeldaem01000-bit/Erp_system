<?php

namespace App\Service\Accounting;

use App\Models\Account;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LedgerPostingService
{
    public function post(array $data): JournalEntry
    {
        return DB::transaction(function () use ($data) {

            $totalDebit = collect($data['lines'])
                ->sum('debit');

            $totalCredit = collect($data['lines'])
                ->sum('credit');

            if ($totalDebit != $totalCredit) {
                throw ValidationException::withMessages([
                    'lines' => [
                        'Total debit must equal total credit.'
                    ]
                ]);
            }

            foreach ($data['lines'] as $line) {

                $account = Account::findOrFail(
                    $line['account_id']
                );

                if (!$account->is_postable) {
                    throw ValidationException::withMessages([
                        'account_id' => [
                            "Account {$account->name} is not postable."
                        ]
                    ]);
                }
            }

            $entry = JournalEntry::create([
                'reference' => $this->generateReference(),
                'entry_date' => $data['entry_date'],
                'description' => $data['description'] ?? null,
                'status' => 'posted',
            ]);

            foreach ($data['lines'] as $line) {

                $entry->lines()->create([
                    'account_id' => $line['account_id'],
                    'debit' => $line['debit'],
                    'credit' => $line['credit'],
                    'description' => $line['description'] ?? null,
                ]);
            }

            return $entry;
        });
    }

    private function generateReference(): string
    {
        $lastId = JournalEntry::max('id') + 1;

        return 'JV-' . str_pad(
            $lastId,
            6,
            '0',
            STR_PAD_LEFT
        );
    }

    public function getAll()
{
    return JournalEntry::query()
        ->with([
            'lines.account'
        ])
        ->latest()
        ->paginate(20);
}


public function find(int $id): JournalEntry
{
    return JournalEntry::with([
        'lines.account'
    ])->findOrFail($id);
}

public function reverse(   JournalEntry $entry
): JournalEntry
{
    if ($entry->status === 'reversed') {

        throw ValidationException::withMessages([
            'entry' => [
                'Entry already reversed.'
            ]
        ]);
    }

    return DB::transaction(
        function () use ($entry) {

            $reverseEntry = JournalEntry::create([
                'reference' => $this->generateReference(),
                'entry_date' => now(),
                'description' => 'Reverse: ' . $entry->reference,
                'status' => 'posted',
                'reversed_entry_id' => $entry->id,
            ]);

            foreach ($entry->lines as $line) {

                $reverseEntry->lines()->create([
                    'account_id' => $line->account_id,

                    'debit' => $line->credit,

                    'credit' => $line->debit,

                    'description' => $line->description,
                ]);
            }

            $entry->update([
                'status' => 'reversed'
            ]);

            return $reverseEntry;
        }
    );
}
}