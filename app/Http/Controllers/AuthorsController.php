<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Author;
use App\Transformer\AuthorTransformer;

class AuthorsController extends Controller
{
    public function index()
    {
        $authors = Author::with('ratings')->get();
        return $this->collection(
            $authors,
            new AuthorTransformer()
        );
    }

    public function show($id)
    {
        return $this->item(Author::findOrFail($id), new AuthorTransformer());
    }

    private function validateAuthor(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'gender' => [
                'required',
                'regex:/^(male|female)$/i',
            ],
            'biography' => 'required'
        ], [
            'gender.regex' => "Gender format is invalid: must equal 'male' or 'female'"
        ]);
    }

    public function store(Request $request)
    {
        $this->validateAuthor($request);

        $author = Author::create($request->all());
        $data = $this->item($author, new AuthorTransformer());

        return response()->json($data, 201, [
            'Location' => route('authors.show', ['id' => $author->id])
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validateAuthor($request);

        $author = Author::findOrFail($id);
        
        $author->fill($request->all());
        $author->save();    
        
        $data = $this->item($author, new AuthorTransformer());
        return response()->json($data, 200);
    }

    public function destroy($id)
    {
        Author::findOrFail($id)->delete();
        return response(null, 204);
    }

}
