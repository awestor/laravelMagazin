<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProductControlPanelController;
use App\Http\Controllers\CommentController;


/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', [MainPageController::class, 'index'])->name('MainPage.index');
Route::get('/favourites', [MainPageController::class, 'index1'])->name('Favourites.index');
Route::get('/basket', [MainPageController::class, 'index1'])->name('Basket.index');
Route::get('/account', [MainPageController::class, 'index1'])->name('Account.index');


Route::get('/mainPage/menuData', [MainPageController::class, 'getMenuData'])->name('mainPage.getData');


Route::get('/category/{name}', [CategoryController::class, 'showCategory'])->name('category.showParent');
Route::get('/categoryView/{name}', [CategoryController::class, 'showProducts'])->name('categoryView');
Route::post('/load-products', [CategoryController::class, 'loadProducts']);


Route::get('/productInfoList/{id}', [ProductController::class, 'show'])->name('productInfoList');
Route::get('/getProductInfo', [ProductController::class, 'getProductInfo'])->name('getProductInfo');
Route::post('/prodReviews', [ProductController::class, 'getProdReviews'])->name('prodReviews');
Route::get('/product-images', [ProductController::class, 'getImages'])->name('getProductImages');
Route::get('/favorite/status', [FavoriteController::class, 'checkFavoriteStatus'])->name('favorite.status');


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');


Route::get('/home', [MainPageController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/mostProducts', [ProductController::class, 'getMostProducts'])->name('products.list');


Route::middleware(['auth.redirect'])->group(function () {
    Route::get('/order', [OrderController::class, 'show'])->name('order.index');
    Route::post('/addToOrder', [OrderController::class, 'addToOrder'])->name('addToOrder');
    Route::post('/order/update/{orderItemId}', [OrderController::class, 'updateQuantity'])->name('order.update');  // Изменение количества товара
    Route::post('/order/delete/{orderItemId}', [OrderController::class, 'removeFromOrder'])->name('order.delete');  // Удаление товара
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('order.checkout');  // Оплата заказа
    Route::post('/order/update-batch', [OrderController::class, 'updateBatch'])->name('order.updateBatch');


    Route::post('/comment/store', [CommentController::class, 'store'])->name('storeComment');


    Route::get('/favorites', [FavoriteController::class, 'show'])->name('favorites.index');
    Route::post('/favorite/toggle', [FavoriteController::class, 'toggleFavorite']);
    Route::post('/favorite/remove', [FavoriteController::class, 'removeFromFavorites'])->name('favorite.remove');


    Route::get('/account', [AccountController::class, 'show'])->name('account.show');
    Route::post('/account/update', [AccountController::class, 'updateProfile'])->name('account.update');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

});


Route::middleware(['auth', 'check.manage.products'])->prefix('product')->group(function () {
    Route::get('/control-panel', [ProductControlPanelController::class, 'index'])->name('productControlPanel'); 
    Route::get('/{product}/edit', [ProductControlPanelController::class, 'edit'])->name('editProduct'); 
    Route::post('/{product}/update', [ProductControlPanelController::class, 'update'])->name('updateProduct'); 
    Route::delete('/{product}/delete', [ProductControlPanelController::class, 'destroy'])->name('deleteProduct');
    Route::get('/product/add', [ProductControlPanelController::class, 'create'])->name('addProduct');
    Route::post('/product/store', [ProductControlPanelController::class, 'store'])->name('storeProduct');
});
