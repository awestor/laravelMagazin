<?php

namespace App\Http\Controllers;


use App\Services\CategoryService;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryFilterRequest;

class CategoryController extends Controller
{
    public function __construct(protected readonly CategoryService $categoryService)
    {    }

    public function show($name)
    {
        $data = $this->categoryService->getViewData('show');

        return view('categories.categoryView', $data, compact('name'));
    }

    public function showCategory($name)
    {
        $data = $this->categoryService->getViewData('showCategory');
        $parCategory = $this->categoryService->getParentCategories($name);

        if($parCategory->isEmpty()){
            return redirect()->route('categoryView', compact('name'));
        }

        return view('categories.parentList', $data, compact('parCategory'));
    }

    public function showProducts($name)
    {
        $data = $this->categoryService->getViewData('showProducts');

        return view('categories.categoryView', $data, compact('name'));
    }

    public function loadProducts(CategoryFilterRequest $request)
    {
        $products = $this->categoryService->getFilteredProducts($request->validated());
        return response()->json($products);
    }

    public function index1()
    {
        return redirect('/');
    }
}
