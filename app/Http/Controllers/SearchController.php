<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Crypt;
use App\Services\ProductService;

class SearchController extends Controller
{
    protected $productService;
    
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function search(Request $request)
    {
        $data = $this->productService->getAssets('search');

        $query = $request->input('query');
        $products = $this->productService->searchProducts($query);

        if ($products->count() === 1) {
            return redirect()->route('productInfoList', ['id' => $products->first()->encrypted_id]);
        }

        return view('components.results', $data, ['products' => $products, 'query' => $query]);
    }
}
