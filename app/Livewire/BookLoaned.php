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
            ->paginate(4);
    }

    public function render()
    {
        return view('livewire.book-loaned')
            ->layout(AppLayout::class, ['title' => 'Books Loaned']);
    }
}
