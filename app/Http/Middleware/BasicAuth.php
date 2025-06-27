<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BasicAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil username & password dari header Basic Auth
        $username = $request->getUser();
        $password = $request->getPassword();

        // Jika tidak ada kredensial, langsung tolak.
        if (!$username || !$password) {
            return $this->unauthorized();
        }

        // Tentukan tipe login (email atau username)
        $loginType = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginType => $username,
            'password' => $password,
        ];

        // Auth::once() melakukan percobaan login satu kali untuk request ini saja (stateless)
        if (Auth::once($credentials)) {
            // Jika berhasil, lanjutkan request ke controller/tujuan
            return $next($request);
        }

        // Jika gagal, kirim response Unauthorized
        return $this->unauthorized();
    }

    /**
     * Mengembalikan response Unauthorized 401.
     */
    private function unauthorized(): Response
    {
        return response()->json([
            'message' => 'Authentication Required.'
        ], 401, ['WWW-Authenticate' => 'Basic']);
    }
}
