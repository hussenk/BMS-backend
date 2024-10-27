<?php

namespace App\Http\Controllers\V1;

use App\Enums\OrderBy;
use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Http\Requests\V1\StoreAuthorRequest;
use App\Http\Requests\V1\UpdateAuthorRequest;
use App\Http\Resources\V1\BaseResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'per_page' => 'numeric',
            'order_by' => new Enum(OrderBy::class)
        ]);

        $query = Author::query()

            ->orderBy('name', $request->order_by ?? 'asc');

        if ($request->has('per_page')) {
            $data =  $query->pageant($request->per_page);
        } else {
            $data = $query->get();
        }

        return  BaseResource::collection($data);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuthorRequest $request)
    {
        $inputs = $request->validated();

        $data = Author::create($inputs);

        return response()->json([
            'data' => $data,
            'message' => 'Created Author'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = Author::findOrFail($id);

        return response()->json([
            'data' => $data,
            'message' => 'Show Author'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthorRequest $request,  $id)
    {
        $inputs = $request->validated();

        $data = Author::findOrFail($id);

        $data->update($inputs);

        return response()->json([
            'data' => $data,
            'message' => 'Updated Author'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UpdateAuthorRequest $request, $id)
    {
        $inputs = $request->validated();

        $data = Author::findOrFail($id);

        $data->delete();

        return response()->json([
            'data' => $data,
            'message' => 'deleted Author'
        ], 200);
    }
}
