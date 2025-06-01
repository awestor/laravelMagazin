<?php
namespace App\Services;

use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    public function storeReview(array $data)
    {
        return Review::create([
            'product_id' => session('product_id'),
            'user_id' => Auth::id(),
            'rating' => $data['rating'],
            'comment' => $data['comment'],
        ]);
    }
}
