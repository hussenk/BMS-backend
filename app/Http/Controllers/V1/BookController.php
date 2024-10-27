<?php

namespace App\Http\Controllers\V1;

use App\Enums\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Http\Requests\V1\StoreBookRequest;
use App\Http\Requests\V1\UpdateBookRequest;
use App\Http\Resources\V1\BaseResource;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //TODO: Search
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        $inputs = $request->validated();
        $data =  Book::create($inputs);

        return response()->json([
            'data' => BaseResource::make($data),
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
            'data' => BaseResource::make($data),
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
            'data' => BaseResource::make($data),
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
            'data' => BaseResource::make($data),
            'message' => "Book Deleted",
        ]);
    }


    public function availableToggle(UpdateBookRequest $request, $id)
    {
        $request->validated();
        $data = Book::findOrFail($id);
        $data->update(['is_available' => !$data->is_available]);

        return response()->json([
            'data' => BaseResource::make($data),
            'message' => "Book available Toggle",
        ]);
    }
}
