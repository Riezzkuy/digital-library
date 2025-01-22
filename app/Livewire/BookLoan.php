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

    public $isLoaned;

    public $activeLoan;

    public $isQueued;

    public $notification;

    public function mount($bookId)
    {
        $this->bookId = $bookId;
        $this->stock = Copy::where('book_id', $bookId)
            ->where('is_borrowed', false)
            ->exists();
        $this->activeLoan = Loan::whereHas('copy', function ($query) use ($bookId) {
            $query->where('book_id', $bookId);
        })
            ->where('user_id', auth()->id())
            ->whereNotNull('loaned_at')
            ->whereNull('returned_at')
            ->first();

        $this->isLoaned = $this->activeLoan !== null;

        $this->isQueued = Loan::whereHas('copy', function ($query) use ($bookId) {
            $query->where('book_id', $bookId);
        })
            ->where('user_id', auth()->id())
            ->whereNull('loaned_at')
            ->whereNull('returned_at')
            ->exists();
    }

    public function loanBook($bookId): void
    {
        if (!auth()->check()) {
            redirect()->route('login');
        }

        $loanCount = Loan::where('user_id', auth()->id())->thisWeek()->count();

        if ($loanCount >= 2) {
            $this->notification = __('You have reached the maximum loan limit for this week.');
            return;
        }

        $this->copy = Copy::where('book_id', $bookId)
            ->where('is_borrowed', false)
            ->firstOrFail();

        $loan = $this->copy->loans()->create([
            'user_id' => auth()->id(),
            'loaned_at' => now(),
        ]);

        $this->copy->update(['is_borrowed' => true]);

        ReturnBookJob::dispatch($loan)->delay(now()->addMinutes(1));

        $this->stock = false;
        $this->isLoaned = true;
        $this->activeLoan = $loan;

        $this->redirect('/loaned', navigate: true);
    }

    public function queueBook($bookId): void
    {
        if (!auth()->check()) {
            redirect()->route('login');
        }

        $loanCount = Loan::where('user_id', auth()->id())->thisWeek()->count();

        if ($loanCount >= 2) {
            $this->addError('stock', 'You have reached the maximum loan limit for this week.');
            return;
        }

        $this->copy = Copy::where('book_id', $bookId)
            ->where('is_borrowed', true)
            ->orderBy('loaned_at', 'asc')
            ->firstOrFail();

        $loan = $this->copy->loans()->create([
            'user_id' => auth()->id()
        ]);

        $this->isQueued = true;

        $this->redirect('/queued', navigate: true);
    }

    public function cancelQueue($bookId): void
    {
        if (!auth()->check()) {
            redirect()->route('login');
        }

        $loan = Loan::whereHas('copy', function ($query) use ($bookId) {
            $query->where('book_id', $bookId);
        })
            ->where('user_id', auth()->id())
            ->whereNull('loaned_at')
            ->whereNull('returned_at')
            ->firstOrFail();

        $loan->delete();

        $this->isQueued = false;

        $this->redirect('/queued', navigate: true);
    }

    public function returnBook($loanId)
    {
        if (!auth()->check()) {
            redirect()->route('login');
        }

        $loan = Loan::findOrFail($loanId);

        $loan->update([
            'returned_at' => now(),
        ]);

        $loan->copy->update([
            'is_borrowed' => false,
        ]);

        // if there is a queued loan, loan it
        $queuedLoan = Loan::where('copy_id', $loan->copy_id)
            ->whereNull('loaned_at')
            ->whereNull('returned_at')
            ->first();

        if ($queuedLoan) {
            $queuedLoan->update([
                'loaned_at' => now(),
            ]);

            $queuedLoan->copy->update([
                'is_borrowed' => true,
            ]);
        }

        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.book-loan', [
            'stock' => $this->stock,
            'isLoaned' => $this->isLoaned,
            'activeLoan' => $this->activeLoan,
            'isQueued' => $this->isQueued,
        ]);
    }
}
