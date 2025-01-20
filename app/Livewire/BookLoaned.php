<?php

namespace App\Livewire;

use App\Models\Loan;
use App\View\Components\AppLayout;
use Livewire\Attributes\Computed;
use Livewire\Component;

class BookLoaned extends Component
{
    #[Computed()]
    public function loans()
    {
        return Loan::where('user_id', auth()->id())
            ->with('copy.book')
            ->whereNotNull('loaned_at')
            ->whereNull('returned_at')
            ->paginate(4);
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
        return view('livewire.book-loaned')
            ->layout(AppLayout::class, ['title' => 'Books Loaned']);
    }
}
