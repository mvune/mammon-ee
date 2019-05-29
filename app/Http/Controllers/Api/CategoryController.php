<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Jobs\AssignCategoriesToTransactions;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Get a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CategoryResource::collection(Auth::user()->categoriesWithFilters()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $category = Auth::user()->categories()->create($request->except('rules'));
        $category->transactionFilters()->createMany($request->rules);

        $this->assignCategoriesToTransactions();
        
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->except('rules'));
        $category->transactionFilters()->delete();
        $category->transactionFilters()->createMany($request->rules);

        $this->assignCategoriesToTransactions();
        
        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        $this->assignCategoriesToTransactions();
    }

    /**
     * Change order/priority of categories.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function order(Request $request)
    {
        $request->validate([
            '*.id' => 'required',
            '*.priority' => 'required|integer|max:32767',
        ]);

        $categories = Auth::user()->categories;
        $ids = $categories->pluck('id')->toArray();

        foreach ($request->all() as $category) {
            if (in_array($category['id'], $ids)) {
                $categories
                    ->where('id', $category['id'])
                    ->first()
                    ->update(['priority' => $category['priority']]);
            }
        }

        $this->assignCategoriesToTransactions();
    }

    private function assignCategoriesToTransactions()
    {
        AssignCategoriesToTransactions::dispatch(Auth::user());
    }
}
