<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // LOGIKA REDIRECT BERDASARKAN ROLE
        if ($request->user()->role === 'admin') {
            // Jika yang login admin, arahkan ke dashboard admin
            // (Pastikan kamu membuat route '/admin/dashboard' nanti)
            return redirect()->intended('/admin/dashboard'); 
        }

        // Jika yang login user biasa, arahkan ke halaman utama/katalog
        // (Pastikan kamu membuat route '/katalog' nanti)
        return redirect()->intended('/katalog');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
