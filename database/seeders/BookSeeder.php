<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = Book::factory(10)->create([
            'publisher_id' => 1,
        ]);

        $author = Author::first();
        $category = Category::first();

        foreach ($books as $book) {
            $book->authors()->attach($author->id);
            $book->categories()->attach($category->id);
        }
    }
}
