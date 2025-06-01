<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Services\CommentService;
use Illuminate\Http\RedirectResponse;


class CommentController extends Controller
{
    public function __construct(protected readonly CommentService $commentService)
    {    }

    public function store(StoreCommentRequest $request): RedirectResponse
    {
        $this->commentService->storeReview($request->validated());

        return redirect()->back()->with('success', 'Отзыв успешно добавлен!');
    }
}
