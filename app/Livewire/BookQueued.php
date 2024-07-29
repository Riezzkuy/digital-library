<?php

namespace App\Livewire;

use App\Models\Loan;
use App\View\Components\AppLayout;
use Livewire\Attributes\Computed;
use Livewire\Component;

class BookQueued extends Component
{

    #[Computed()]
    public function queues()
    {
        return Loan::where('user_id', auth()->id())
            ->with('copy.book')
            ->paginate(4);
    }

    public function render()
    {
        return view('livewire.book-queued')
            ->layout(AppLayout::class, ['title' => 'Queued']);
    }
}
