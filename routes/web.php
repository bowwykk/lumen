<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/hello/world', function () use ($router) {
    return "Hello world!";
});

$router->get('/hello/{name}', ['middleware' => 'hello', function ($name) { 
    return "Hello {$name}";
}]);

$router->get('/request', function (Illuminate\Http\Request $request) {
    return "Hello " . $request->get('name', 'stranger');
});

$router->get('/response', function (Illuminate\Http\Request $request) {
    if ($request->wantsJson()) {
        return response()->json(['greeting' => 'Hello stranger1']);
    }

    return response()
        ->make('Hello stranger', 200, ['Content-Type' => 'text/plain']);
});

$router->get('/books', 'BooksController@index');
// $router->get('/books/{id}', 'BooksController@show');
$router->get('/books/{id:[\d]+}', 'BooksController@show');

$router->post('/books', 'BooksController@store');

$router->get('/books/{id:[\d]+}', [
    'as' => 'books.show',
    'uses' => 'BooksController@show'
]);

$router->put('/books/{id:[\d]+}', 'BooksController@update');

$router->delete('/books/{id:[\d]+}', 'BooksController@destroy');

$router->group([
    'prefix' => '/authors',
    'namespace' => '\App\Http\Controllers'
], function () use ($router) {
    $router->get('/', 'AuthorsController@index');
    $router->post('/', 'AuthorsController@store');
    $router->get('/{id:[\d]+}', [
        'as' => 'authors.show',
        'uses' => 'AuthorsController@show'
    ]);
    $router->put('/{id:[\d]+}', 'AuthorsController@update');
    $router->delete('/{id:[\d]+}', 'AuthorsController@destroy');

    // Author ratings
    $router->post('/{id:[\d]+}/ratings', 'AuthorsRatingsController@store');
    $router->delete(
        '/{authorId:[\d]+}/ratings/{ratingId:[\d]+}',
        'AuthorsRatingsController@destroy'
    );
});


$router->group([
    'prefix' => '/bundles',
    'namespace' => '\App\Http\Controllers'
], function () use ($router) {
    $router->get('/{id:[\d]+}', [
        'as' => 'bundles.show',
        'uses' => 'BundlesController@show'
    ]);
    $router->put(
        '/{bundleId:[\d]+}/books/{bookId:[\d]+}',
        'BundlesController@addBook'
    );
    $router->delete(
        '/{bundleId:[\d]+}/books/{bookId:[\d]+}',
        'BundlesController@removeBook'
    );
});