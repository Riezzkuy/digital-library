<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Loan;
use Carbon\Carbon;
use Filament\Forms\Components\Card;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class BookList extends Component
{
    use WithPagination;

    #[Url()]
    public $search = '';

    #[On('search')]
    public function updateSearch($search)
    {
        $this->search = $search;
        $this->resetPage();
    }

    #[Computed()]
    public function books()
    {
        return Book::search($this->search)
            ->paginate(4);
    }

    public function borrowBook($bookId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        Loan::create([
            'book_id' => $bookId,
            'user_id' => auth()->id(),
            'loaned_at' => now(),
            'returned_at' => Carbon::now()->addDays(7),
        ]);

        $this->dispatch('loanAdded');
    }

    public function isBorrowed($bookId)
    {
        return Loan::where('book_id', $bookId)
            ->where('user_id', auth()->id())
            ->where('status', 'borrowed')
            ->exists();
    }

    public function isBorrowedByAnother($bookId)
    {
        return Loan::where('book_id', $bookId)
            ->where('user_id', '!=', auth()->id())
            ->where('status', 'borrowed')
            ->exists();
    }

    public function render()
    {
        return view('livewire.book-list');
    }
}
