<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AccountService;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function __construct(protected AccountService $accountService)
    {    }

    public function show()
    {
        $orders = $this->accountService->getUserOrders();
        $totalSpent = $this->accountService->calculateTotalSpent($orders);
        $data = $this->accountService->getViewData();
        $user = Auth::user();

        return view('accounts.account', $data, compact(
            'user', 'orders', 'totalSpent',
        ));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $result = $this->accountService->updateProfile(Auth::user(), $request->validated());
        
        return response()->json(['message' => $result['message']], $result['status'] ? 200 : 400);
    }
}
