<?php

namespace App\Livewire;

use App\Jobs\ReturnBookJob;
use App\Models\Book;
use App\Models\Copy;
use App\Models\Loan;
use Livewire\Attributes\Computed;
use Livewire\Component;

class BookLoan extends Component
{
    public Copy $copy;

    public $bookId;

    public $stock;

    public function mount($bookId)
    {
        $this->bookId = $bookId;
    }

    public function stock($bookId)
    {
        Copy::where('book_id', $bookId)
            ->where('is_borrowed', false)
            ->exists();
    }

    public function loanBook($bookId): void
    {
        if (!auth()->check()) {
            redirect()->route('login');
        }

        $this->copy = Copy::where('book_id', $bookId)
        ->where('is_borrowed', false)
        ->firstOrFail();

        $loan = $this->copy->loans()->create([
            'user_id' => auth()->id(),
            'loaned_at' => now(),
        ]);

        $this->copy->update(['is_borrowed' => true]);

        ReturnBookJob::dispatch($loan)->delay(now()->addDays(7));

        $this->dispatch('close');
    }

    public function queueBook($bookId): void
    {
        if (!auth()->check()) {
            redirect()->route('login');
        }

        $this->copy = Copy::where('book_id', $bookId)
        ->where('is_borrowed', true)
        ->orderBy('loaned_at', 'asc')
        ->firstOrFail();

        $loan = $this->copy->loans()->create([
            'user_id' => auth()->id()
        ]);

        $this->dispatch('close');
    }

    public function render()
    {
        return view('livewire.book-loan');
    }
}
