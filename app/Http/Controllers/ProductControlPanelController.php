<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductControlPanelService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;

class ProductControlPanelController extends Controller
{
    protected ProductControlPanelService $service;

    public function __construct(ProductControlPanelService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $data = $this->service->getViewData('productControlPanel');
        $products = $this->service->getUserProducts();
        $soldProducts = $this->service->getSoldProductsLastMonth();
        
        return view('products.productControlPanel', $data, compact('products', 'soldProducts'));
    }

    public function create()
    {
        $data = $this->service->getViewData('addProduct');
        $productData = $this->service->getProductData();

        return view('products.addProduct', $data, $productData);
    }

    public function edit(Product $product)
    {
        $data = $this->service->getViewData('editProduct');
        $productData = $this->service->getProductData();
        return view('products.editProduct', $data, array_merge(['product' => $product], $productData));
    }

    public function update(Request $request, Product $product)
    {
        $this->service->updateProduct($product, $request->all());
        return redirect()->route('productControlPanel')->with('success', 'Товар обновлен.');
    }

    public function destroy(Product $product)
    {
        $this->service->deleteProduct($product);
        return redirect()->route('productControlPanel')->with('success', 'Товар удален.');
    }

    public function store(StoreProductRequest $request)
    {

        $product = $this->service->createProductWithImages($request->validated());

        return redirect()->route('productControlPanel')->with('success', 'Товар успешно добавлен!');
    }
}
