<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('welcome', [
            'books' => Book::all()->take(3)
        ]);
    }
}
