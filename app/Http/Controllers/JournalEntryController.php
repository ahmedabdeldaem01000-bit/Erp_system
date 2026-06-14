<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJournalEntryRequest;
use App\Http\Resources\JournalEntryResource;
use App\Models\JournalEntry;
use App\Service\accounting\LedgerPostingService;

class JournalEntryController extends Controller
{
    public function __construct(
        private LedgerPostingService $ledgerPostingService
    ) {
    }

    public function store(
        StoreJournalEntryRequest $request
    ) {
        $entry = $this->ledgerPostingService
            ->post($request->validated());

        return new JournalEntryResource(
            $entry->load('lines.account')
        );
    }


    public function index()
{
    $entries = $this->ledgerPostingService
        ->getAll();

    return JournalEntryResource::collection(
        $entries
    );
} 
    public function show(int $id)
{
    $entry = $this->ledgerPostingService
        ->find($id);

    return new JournalEntryResource(
        $entry
    );
}

public function reverse(
    int $id
)
{
    $entry = JournalEntry::with(
        'lines'
    )->findOrFail($id);

    $reverse = $this->ledgerPostingService
        ->reverse($entry);

    return new JournalEntryResource(
        $reverse->load('lines.account')
    );
}
}

// 