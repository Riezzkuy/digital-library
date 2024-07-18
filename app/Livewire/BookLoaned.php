<?php

namespace App\Livewire;

use App\View\Components\AppLayout;
use Livewire\Attributes\Computed;
use Livewire\Component;

class BookLoaned extends Component
{
    #[Computed()]
    public function books()
    {
        return auth()->user()->books()->paginate(4);
    }

    public function render()
    {
        return view('livewire.book-loaned')
            ->layout(AppLayout::class, ['title' => 'Books Loaned']);
    }
}
