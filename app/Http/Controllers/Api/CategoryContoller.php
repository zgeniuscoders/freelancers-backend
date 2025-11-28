<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryContoller extends Controller
{
    public function index()
    {
        $categories = Category::paginate(20);
        return CategoryResource::collection($categories);
    }

    public function show(int $id)
    {
        $category = Category::query()->firstOrFail($id);
        return new CategoryResource($category);
    }

    public function store(AddCategoryRequest $request)
    {
        $category = Category::query()->create($request->validated());
        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $request, int $id)
    {
        $category = Category::query()->firstOrFail($id);
        $category->update($request->validated());
        return new CategoryResource($category);
    }

    public function destroy(int $id){
        $category = Category::query()->firstOrFail($id);
        $category->delete();
    }
}
