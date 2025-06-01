<?php
namespace App\Services;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;

class FavoriteService
{
    public function add($userId, $productId)
    {
        $exists = Favorite::where('user_id', $userId)->where('product_id', $productId)->exists();
        
        if (!$exists) {
            Favorite::create([
                'user_id' => $userId,
                'product_id' => $productId
            ]);
            return ['message' => 'Товар добавлен в избранное'];
        }

        return ['message' => 'Товар уже в избранном'];
    }

    public function remove($userId, $hashId)
    {
        $productId = Crypt::decrypt($hashId);
        Favorite::where('user_id', $userId)->where('product_id', $productId)->delete();
        return ['message' => 'Товар удалён из избранного'];
    }

    public function get($userId)
    {
        return Product::whereIn('product_id', Favorite::where('user_id', $userId)->pluck('product_id'))->get();
    }

    public function toggleFavorite(): array
    {
        $userId = Auth::id();
        $productId = Session::get('product_id');
        $favorite = Favorite::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($favorite) {
            $favorite->delete();
            return ['success' => true, 'message' => 'Удалено из избранного'];
        } else {
            Favorite::create(['user_id' => $userId, 'product_id' => $productId]);
            return ['success' => true, 'message' => 'Добавлено в избранное'];
        }
    }

    public function GetFavoriteStatus()
    {
        $userId = Auth::id();
        $productId = Session::get('product_id');
        $favorite = Favorite::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($favorite) {
            return true;
        } else {
            return false;
        }
    }

    public function getViewData(string $page)
    {
        $data = [
            'styles' => [
                'css/header.css',
                'css/pages/favorites.css',
            ],
            'scripts' => [
                'js/pages/favorite.js',
            ],
        ];
    
        return $data;
    }

    public function getFavorites()
    {
        $user = Auth::user();
        $favorites = $user->favorites()->with(['product.images', 'product.reviews'])->get();
    
        return $favorites;
    }
}
