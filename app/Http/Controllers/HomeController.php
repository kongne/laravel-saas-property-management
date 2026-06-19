<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.index');
    }

    public function pricing()
    {
        return view('home.pricing');
    }

    public function features()
    {
        return view('home.features');
    }

    public function contact()
    {
        return view('home.contact');
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string|max:5000',
        ]);

        return redirect()->route('home')->with('success', 'Thank you! We will get back to you soon.');
    }
}
