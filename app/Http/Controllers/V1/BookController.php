<?php

namespace App\Http\Controllers\V1;

use App\Enums\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Http\Requests\V1\StoreBookRequest;
use App\Http\Requests\V1\UpdateBookRequest;
use App\Http\Resources\V1\BaseResource;
use App\Http\Resources\V1\BooksResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate(['per_page' => 'numeric',]);


        $query = QueryBuilder::for(Book::class)
            ->when(Auth::user()->role == UserRoleEnum::User, function ($q) {
                $q->available();
            })
            ->allowedFilters([
                'title',
                AllowedFilter::exact('author_id'),
                AllowedFilter::exact('year'),
                'isbn',
                AllowedFilter::exact('is_available'),
                AllowedFilter::scope('created_at_from'),
                AllowedFilter::scope('created_at_to'),
                AllowedFilter::scope('updated_at_from'),
                AllowedFilter::scope('updated_at_to'),
            ])
            ->allowedSorts([
                'id',
                'title',
                'author_id',
                'year',
                'isbn',
                'created_at',
                'updated_at',
                'is_available',
            ]);

        if ($request->has('per_page')) {
            $data =  $query->paginate($request->per_page);
        } else {
            $data = $query->get();
        }

        return BooksResource::collection($data)->additional([
            "meta" => [
                'filterable' => [
                    'title',
                    'author_id',
                    'year',
                    'isbn',
                    'created_at',
                    'updated_at',
                    'is_available',
                ],

                'sortable' => [
                    'title',
                    'author_id',
                    'year',
                    'isbn',
                    'created_at',
                    'updated_at',
                    'is_available',
                ]
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        $inputs = $request->validated();
        $data =  Book::create($inputs);

        return response()->json([
            'data' => BooksResource::make($data),
            'message' => "Book created",
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data =  Book::when(Auth::user()->role == UserRoleEnum::User, function ($q) {
            $q->available();
        })->findOrFail($id);

        return response()->json([
            'data' => BooksResource::make($data),
            'message' => "Book Show",
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, $id)
    {
        $inputs = $request->validated();

        $data = Book::findOrFail($id);
        $data->update($inputs);
        return response()->json([
            'data' => BooksResource::make($data),
            'message' => "Book Updated",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UpdateBookRequest $request, $id)
    {
        $request->validated();

        $data = Book::findOrFail($id);
        $data->delete();
        return response()->json([
            'data' => BooksResource::make($data),
            'message' => "Book Deleted",
        ]);
    }


    public function availableToggle(UpdateBookRequest $request, $id)
    {
        $request->validated();
        $data = Book::findOrFail($id);
        $data->update(['is_available' => !$data->is_available]);

        return response()->json([
            'data' => BooksResource::make($data),
            'message' => "Book available Toggle",
        ]);
    }
}
