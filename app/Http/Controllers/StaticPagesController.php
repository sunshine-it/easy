<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPagesController extends Controller
{
    //
    public function home()
    {
        // return 'Home-首页';
        return view('static_pages.home');
    }

    public function help()
    {
        // return 'Help-帮助';
        return view('static_pages.help');
    }

    public function about()
    {
        // return 'About-关于';
        return view('static_pages.about');
    }
}
