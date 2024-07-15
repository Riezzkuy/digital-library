<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        return view('books.index', [
            'books' => Book::all()
        ]);
    }
    public function show(Book $book)
    {
        return view('books.show', [
            'book' => $book
        ]);
    }
}
