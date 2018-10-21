<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use App\Book;
use App\Transformer\BookTransformer;

/**
* Class BooksController
* @package App\Http\Controllers
*/
class BooksController extends Controller
{
    /**
    * GET /books
    * @return array
    */
    public function index()
    {
        
        // $books = Book::all()->toArray();
        // return response()->json($books);

        //2
        // return ['data' => Book::all()->toArray()];

        //3
        // return $this->fractal->collection($books, new BookTransformer());

        //4
        return $this->collection(Book::all(), new BookTransformer());
    }

    public function show($id)
    {
        \Log::info('====show====');
        //1
        // try {
            // $book =  Book::findOrFail($id);
            // return response()->json($book);
        // } catch (ModelNotFoundException $e) {
        //     return response()->json([
        //         'error' => [
        //             'message' => 'Book not found'
        //         ]
        //     ], 404);
        // }
        
        //2
        // return ['data' => Book::findOrFail($id)];

        //3
        return $this->item(Book::findOrFail($id), new BookTransformer());
    }
    /**
    *POST /books
    *@param Request $request
    *@return \Symfony\Component\HttpFoundation\Response
    */
    public function store(Request $request)
    {
        // $book = Book::create($request->all());

        // return response()->json(['data' => $book->toArray()], 201, [
        //          'Location' => route('books.show', ['id' => $book->id])
        // ]);

        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'author_id' => 'required|exists:authors,id'
        ], [
            'description.required' => 'Please provide a :attribute.'
        ]
    );

        //2
        $book = Book::create($request->all());
        $data = $this->item($book, new BookTransformer());
        
        return response()->json($data, 201, [
            'Location' => route('books.show', ['id' => $book->id])
        ]);
        
    }

    public function update(Request $request, $id)
    {
        try {
            $book = Book::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => [
                    'message' => 'Book not found'
                ]
                ], 404);
        }

        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'author_id' => 'exists:authors,id'
        ], [
            'description.required' => 'Please provide a :attribute.'
        ]);

        $book->fill($request->all());
        $book->save();
        
        // return $book;

        return $this->item($book, new BookTransformer());
    }

    public function destroy($id)
    {
        try {
            $book = Book::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => [
                    'message' => 'Book not found'
                ]
                ], 404);
        }
        $book->delete();

        return response(null, 204);
    }
}
