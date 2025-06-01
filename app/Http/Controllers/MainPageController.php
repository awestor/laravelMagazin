<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MainPageService;

class MainPageController extends Controller
{
    protected $mainPageService;

    public function __construct(MainPageService $mainPageService)
    {
        $this->mainPageService = $mainPageService;
    }

    public function index1()
    {
        return redirect('/');
    }
    
    public function index()
    {
        $menuData = $this->mainPageService->getRootCategories();
        $data = $this->mainPageService->getViewData('index');        
        
        return view('mainPage.mainPageView', $data, compact('menuData'));
    }
    
    public function getMenuData(){
        $menuData = $this->mainPageService->getRootCategories();
        return response()->json($menuData);
    }

}
