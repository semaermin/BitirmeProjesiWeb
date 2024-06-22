<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use App\Models\Test;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{

    public function testList()
    {
        $tests = Test::where('is_video', 0)->latest()->take(10)->get();
        return response()->json(['tests' => $tests]);
    }

    public function testData($slug)
    {
        try {
            $test = Test::where('slug', $slug)
                        ->with('questions', 'questions.answers', 'questions.matchingOptions')
                        ->firstOrFail();

            // Mevcut testin adını al
            $testName = $test->name;

            return response()->json(['testName' => $testName, 'test' => $test], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Bir hata oluştu.'], 500);
        }
    }

}
