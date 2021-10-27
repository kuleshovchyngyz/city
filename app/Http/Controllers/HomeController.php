<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function documentation()
    {
        return view('documentation');
    }
    public function test()
    {
        $time = Carbon::now()->timestamp;
        DB::table("geo_districts")
            ->update([
                'timestamp' => $time
            ]);
    }
}
