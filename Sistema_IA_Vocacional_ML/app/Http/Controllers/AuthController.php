<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ]);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'grade' => 'required|string',
            'school' => 'required|string|max:255',
        ]);

        try {
            $user = User::crear([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'grade' => $request->grade,
                'school' => $request->school,
            ]);

            Auth::login($user);
            return redirect('/dashboard');
        } catch (\Exception $e) {
            Log::error('Error en registro: ' . $e->getMessage());
            return back()->with('error', 'Error al registrarse. Por favor intenta de nuevo.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
