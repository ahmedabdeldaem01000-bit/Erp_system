<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    /** @use HasFactory<\Database\Factories\JournalEntryFactory> */
    use HasFactory;
    protected $fillable = [
        'reference',
        'entry_date',
        'description',
        'status',
        'reversed_entry_id'
    ];

    public function lines()
    {
        return $this->hasMany(
            JournalEntryLine::class
        );
    }
}
