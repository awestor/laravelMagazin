<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FavoriteService;
use Illuminate\Support\Facades\Auth;


class FavoriteController extends Controller
{
    public function __construct(protected readonly FavoriteService $favoriteService)
    {    }

    public function show()
    {
        $data = $this->favoriteService->getViewData('show');
        $favorites = $this->favoriteService->getFavorites();

        return view('favorites.favoritesView', $data, compact('favorites'));
    }

    public function addToFavorites(Request $request)
    {
        $response = $this->favoriteService->add(Auth::id(), $request->product_id);
        return response()->json($response);
    }

    public function removeFromFavorites(Request $request)
    {
        $response = $this->favoriteService->remove(Auth::id(), $request->product_id);
        return response()->json($response);
    }

    public function toggleFavorite()
    {
        $result = $this->favoriteService->toggleFavorite();
        return response()->json($result);
    }

    public function checkFavoriteStatus()
    {
        if (!Auth::check()) {
            return response()->json(['isFavorite' => false]); 
        }
        
        $isFavorite = $this->favoriteService->GetFavoriteStatus();
        //\Illuminate\Support\Facades\Log::debug('isFavorite:', ['isFavorite' => $isFavorite]);
        return response()->json(['isFavorite' => $isFavorite]);
    }
}
