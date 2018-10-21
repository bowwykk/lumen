
<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder {
    public function run() {
        // DB::table('books')->insert([
        //     'title' => 'War of the Worldes',
        //     'description' => 'A science fiction masterpiece about Martians invading London',
        //     'author' => 'H. G. Wells',
        //     'created_at' => Carbon::now(),
        //     'updated_at' => Carbon::now(),
        // ]);

        // DB::table('books')->insert([
        //     'title' => 'A Wrinkle in Time',
        //     'description' => 'A young girl goes on a mission to save her father who has gone missing after working on a mysterious project called a tesseract.',
        //     'author' => 'Madeleine L\'Engle',
        //     'created_at' => Carbon::now(),
        //     'updated_at' => Carbon::now(),
        // ]);

        //2
        factory(App\Author::class, 10)->create()->each(function ($author) {
            $author->ratings()->saveMany(
                factory(App\Rating::class, rand(20, 50))->make()
            );
            
            $booksCount = rand(1, 5);

            while ($booksCount > 0) {
                $book = factory(App\Book::class)->make();
                $author->books()->save($book);
                $book->ratings()->saveMany(
                    factory(App\Rating::class, rand(20, 50))->make()
                );
                $booksCount--;
            }
        });
    }
}