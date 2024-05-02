<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CsrfTokenController extends Controller
{
    // public function getToken(Request $request)
    // {
    //     $token = csrf_token();

    //     return response()->json(['csrf_token' => $token]);
    // }

    public function getToken(Request $request)
    {
        $token = $request->session()->token();
        return response()->json(['csrf_token' => $token]);
    }

}
