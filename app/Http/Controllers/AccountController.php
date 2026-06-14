<?php
 

namespace App\Http\Controllers;

use App\Models\Account;
use App\Service\Account\AccountService;
use App\Http\Resources\AccountResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;

class AccountController extends Controller
{
    public function __construct(
        private AccountService $accountService
    ) {
    }

    public function index()
    {
        $accounts = $this->accountService->getAll();

        return AccountResource::collection($accounts);
    }

    public function store(
        StoreAccountRequest $request
    ) {
        $account = $this->accountService->create(
            $request->validated()
        );

        return response()->json([
            'message' => 'Account created successfully',
            'data' => new AccountResource($account)
        ], 201);
    }

    public function show(Account $account)
    {
        $account->load('children');

        return new AccountResource($account);
    }

    public function update(
        UpdateAccountRequest $request,
        Account $account
    ) {
        $account = $this->accountService->update(
            $account,
            $request->validated()
        );

        return response()->json([
            'message' => 'Account updated successfully',
            'data' => new AccountResource($account)
        ]);
    }

    public function destroy(Account $account)
    {
        $this->accountService->delete($account);

        return response()->json([
            'message' => 'Account archived successfully'
        ]);
    }
}