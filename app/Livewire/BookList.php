<?php

namespace App\Livewire;

use App\Jobs\ReturnBookJob;
use App\Models\Book;
use App\Models\Copy;
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

    public $isBorrowed = false;

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

    public function getQueuedCountForBook($bookId)
    {
        return Loan::whereHas('copy', function ($query) use ($bookId) {
            $query->where('book_id', $bookId);
        })->whereNull('loaned_at')->count();
    }

    public function getStockCountForBook($bookId)
    {
        return Copy::where('book_id', $bookId)
            ->where('is_borrowed', false)
            ->count();
    }

    public function getViewerCountForBook($bookId)
    {
        return Copy::where('book_id', $bookId)
            ->where('is_borrowed', true)
            ->count();
    }

    public function borrow($bookId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $copy = Copy::where('book_id', $bookId)
            ->where('is_borrowed', false)
            ->firstOrFail();

        /* jika stok buku habis maka antri artinya hanya input book_id saja dan user_id
        loaned_at diisi ketika buku sudah ada stoknya dari copy yang statusnya is_borrowed = false */

        $loan = Loan::create([
            'copy_id' => $copy->id,
            'user_id' => auth()->id(),
            'loaned_at' => now(),
        ]);

        $copy->update(['is_borrowed' => true]);

        ReturnBookJob::dispatch($loan)->delay(now()->addDays(7));

        session()->flash('status', 'Book borrowed successfully.');

        $this->dispatch('loanAdded');
    }

    public function queue($bookId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        /* untuk method queue, jika stok buku habis maka antri artinya hanya input book_id saja dan user_id
        copy_id yang digunakan adalah dengan waktu paling awal yang statusnya is_borrowed = true */

        $copy = Copy::where('book_id', $bookId)
            ->where('is_borrowed', true)
            ->orderBy('loaned_at')
            ->firstOrFail();

        Loan::create([
            'copy_id' => $copy->id,
            'user_id' => auth()->id(),
        ]);

        session()->flash('status', 'Book queued successfully.');

        $this->dispatch('loanAdded');
    }

    public function isEmpty($bookId)
    {
        return Copy::where('book_id', $bookId)
            ->where('is_borrowed', false)
            ->doesntExist();
    }

    public function render()
    {
        return view('livewire.book-list');
    }
}
