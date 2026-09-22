<?php

namespace App\Http\Controllers;

use App\Services\Content\HomepageService;
use App\Services\SettingsService;

class HomeController extends Controller
{
    public function index(HomepageService $homepage, SettingsService $settings)
    {
        return view('shop.home', [
            'sections' => $homepage->sections(),
            'title' => null,
        ])->with('announcement', $settings->announcement());
    }
}