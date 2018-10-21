<?php

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    /*
    * See if the response has a header.
    */
    public function seeHasHeader($header) {
        $this->assertTrue(
            $this->response->headers->has($header),
            "Response should have the header '{$header}' but does not."
        );

        return $this;
    }

    /*
    * Asserts that the response header matches a given regular expression
    */
    public function seeHeaderWithRegExp($header, $regexp) {
        $this
            ->seeHasHeader($header)
            ->assertRegExp(
                $regexp,
                $this->response->headers->get($header)
            );

        return $this;
    }

    protected function bookFactory($count = 1)
    {
        $author = factory(\App\Author::class)->create();

        if ($count === 1) {
            $book = factory(\App\Book::class)->make();
            $book->author()->associate($author);
            $book->save();
            return $book;
        } else {
            $books = factory(\App\Book::class, $count)->make();
            $books->each(function ($book) use ($author) {
                $book->author()->associate($author);
                $book->save();
            });
        }

        return $books;
    }

    protected function bundleFactory($bookCount = 2)
    {
        if ($bookCount <= 1) {
            throw new \RuntimeException('A bundle must have two or more books!');
        }
        
        $bundle = factory(\App\Bundle::class)->create();
        $books = $this->bookFactory($bookCount);
        
        $books->each(function ($book) use ($bundle) {
            $bundle->books()->attach($book);
        });
        
        return $bundle;
    }
}
