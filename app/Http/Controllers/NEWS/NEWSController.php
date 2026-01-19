<?php

namespace App\Http\Controllers\NEWS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NEWSController extends Controller
{
    public function index()
    {
        $pageConfigs = ['myLayout' => 'front'];
        
        return view('content.NEWS.index', [
            'pageConfigs' => $pageConfigs
        ]);
    }
}
