<?php

namespace App\Livewire;

use App\Jobs\ReturnBookJob;
use App\Models\Copy;
use Livewire\Component;

class BookLoan extends Component
{
    public Copy $copy;

    public function loanBook($copyId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->copy = Copy::findOrFail($copyId);

        if ($this->copy->is_borrowed) {
            return session()->flash('status', 'This book is already borrowed by another user.');
        }

        $loan = $this->copy->loans()->create([
            'user_id' => auth()->id(),
            'loaned_at' => now(),
        ]);

        $this->copy->update(['is_borrowed' => true]);

        ReturnBookJob::dispatch($loan)->delay(now()->addDays(7));
    }

    public function render()
    {
        return view('livewire.book-loan');
    }
}
