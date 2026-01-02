<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Mostrar el formulario de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Procesar el login
    public function login(Request $request)
    {
        // 1. Validar datos
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Intentar loguear
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // --- NUEVA LÓGICA INTELIGENTE ---
            // Verificamos si hay algo en el carrito
            $cart = session()->get('cart', []);
            
            if(count($cart) > 0) {
                // Si tiene productos, lo mandamos de vuelta al carrito para que pague
                return redirect()->route('cart.index')
                                 ->with('success', '¡Bienvenido! Puedes finalizar tu compra.');
            }
            // --------------------------------

            return redirect()->intended(route('home'))
                             ->with('success', '¡Bienvenido de nuevo!');
        }

        // 3. Si falla
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('home'));
    }

    // Mostrar formulario de registro
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Guardar nuevo usuario
public function register(Request $request)
    {
        // 1. Validar datos (Agregamos cédula y teléfono)
        $request->validate([
            'name' => 'required|string|max:255',
            'cedula' => 'required|string|max:20|unique:users', // <--- NUEVO
            'phone' => 'required|string|max:20',               // <--- NUEVO
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 2. Crear usuario con todos los datos
        $user = \App\Models\User::create([
            'name' => $request->name,
            'cedula' => $request->cedula, // <--- Guardamos Cédula
            'phone' => $request->phone,   // <--- Guardamos Teléfono
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'address' => null, // La dirección la dejamos vacía para después
        ]);

        // 3. Loguear y redirigir
        Auth::login($user);

        return redirect(route('home'))->with('success', '¡Cuenta creada exitosamente!');
    }
}