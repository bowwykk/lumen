<?php

namespace Tests\App\Transformer;

use Laravel\Lumen\Testing\DatabaseMigrations;

use TestCase;
use App\Book;
use Carbon\Carbon;

use App\Transformer\BookTransformer;

use League\Fractal\TransformerAbstract;

class BookTransformerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        
        Carbon::setTestNow(Carbon::now('UTC'));
    }

    public function tearDown()
    {
        parent::tearDown();

        Carbon::setTestNow();
    }

    /** @test **/
    public function it_can_be_initialized()
    {
        $subject = new BookTransformer();
        $this->assertInstanceOf(TransformerAbstract::class, $subject);
    }

    /** @test **/
    public function it_transforms_a_book_model()
    {
        // $book = factory(Book::class)->create();

        $book = $this->bookFactory();
        $subject = new BookTransformer();

        $transform = $subject->transform($book);

        $this->assertArrayHasKey('id', $transform);
        $this->assertArrayHasKey('title', $transform);
        $this->assertArrayHasKey('description', $transform);
        $this->assertArrayHasKey('author', $transform);
        $this->assertArrayHasKey('created', $transform);
        $this->assertArrayHasKey('updated', $transform);
    }
}
