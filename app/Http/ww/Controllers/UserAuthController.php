<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserAuthController extends Controller
{
    function userLogin(Request  $request){
        return $request->all();
    }
}
