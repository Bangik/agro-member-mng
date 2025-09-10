<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class MemberMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    if (Auth::check() && Auth::user()->role === 'member') {
      if (Auth::user()->member->contracts->where('end_date', '>=', now()->format('Y-m-d'))->count() > 0) {
        return $next($request);
      } else {
        abort(403, 'Anda bukan anggota aktif. Silakan hubungi admin untuk memperbarui status keanggotaan Anda.');
      }
    }

    abort(403, 'Unauthorized action.');
  }
}
