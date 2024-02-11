<?php

namespace App\Http\Controllers\UserAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;




class AuthController extends Controller
{
    public function dash() {
        $loginForm1 = Session::get('login_form_1');
        return view('error',compact('loginForm1'));
    }
    public function showLoginForm()
    {
        return view('userAuth.login'); // login.blade.php isimli view dosyasını döndürür
    }
    public function showRegistrationForm()
    {
        return view('userAuth.register');
    }

    // Giriş işlemini gerçekleştiren metod
    public function login(Request $request)
    {
        // Formdan gelen verileri doğrula
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Kullanıcıyı giriş yapmaya çalış
        if (Auth::attempt($credentials)) {
            // Başarılı giriş
            $request->session()->regenerate();

            return redirect()->intended('/error');
        }

        // Giriş başarısız oldu
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function register(Request $request)
    {
        // Formdan gelen verileri doğrula
        $input = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Yeni kullanıcı oluştur
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->is_admin = 0;
        $user->save();

        return redirect('/user/login')->with('success', 'Kayıt başarıyla tamamlandı!');
    }

    // Çıkış işlemini gerçekleştiren metod
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('userAuth.password.request');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

}
