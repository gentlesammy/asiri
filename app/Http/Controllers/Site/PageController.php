<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    //
    public function show_aboutpage(){
        return view("site.about");
    }


    public function show_termspage(){
        return view("site.terms");
    }


    public function show_privacypage(){
        return view("site.privacy");
    }
}
