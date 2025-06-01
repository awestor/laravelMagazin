<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;
use Illuminate\Support\Facades\Crypt;

class ProductController extends Controller
{
    protected $product_id;

    public function __construct(protected readonly ProductService $productService)
    {    }

    public function show($product_id)
    {
        session(['product_id' => Crypt::decrypt($product_id)]);
        return view('products.productView', $this->productService->getViewData());
    }
    
    public function getProductInfo(){
        $product_id = session('product_id');
        $menuData = $this->productService->getProductInfo($product_id);
        //\Illuminate\Support\Facades\Log::debug('menuData:', ['menuData' => $menuData]);
        return response()->json($menuData);
    }

    public function getProdReviews(Request $request){
        $product_id = session('product_id');
        $reviewsData = $this->productService->getProdReviews($product_id, $request->get('page'));
        return response()->json($reviewsData);
    }

    public function getMostProducts(Request $request)
    {
        $page = $request->get('page', 1);
        $products = $this->productService->getProducts($page);

        return response()->json($products->toArray());
    }

    public function getImages()
    {
        $productId = session('product_id');
        $images = $this->productService->getProductImages($productId);
        
        return response()->json($images);
    }
}
