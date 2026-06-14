<?php

namespace App\Service\Account;

use App\Models\Account;

class AccountService
{
    public function getAll()
    {
        return Account::query()
            ->with('children')
            ->whereNull('parent_id')
            ->get();
    }

    public function find(int $id): Account
    {
        return Account::findOrFail($id);
    }

    public function create(array $data): Account
    {
        return Account::create($data);
    }

    public function update(Account $account, array $data): Account
    {
        $account->update($data);

        return $account->fresh();
    }

    public function delete(Account $account): void
    {
        $account->update([
            'is_active' => false
        ]);
    }
}