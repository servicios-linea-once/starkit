<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginHistoryController extends Controller
{
    // Historial global (admin)
    public function index(Request $request): Response
    {
        $histories = LoginHistory::query()
            ->with('user:id,name,email,avatar_url')
            ->when($request->search, fn ($q) =>
            $q->whereHas('user', fn ($u) =>
            $u->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%")
            )
            )
            ->when($request->status !== null && $request->status !== '', fn ($q) =>
            $q->where('successful', (bool) $request->status)
            )
            ->latest('logged_in_at')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/LoginHistory/Index', [
            'histories' => $histories,
            'filters'   => $request->only(['search', 'status']),
        ]);
    }

    // Historial del usuario autenticado (Settings)
    public function mine(Request $request): Response
    {
        $histories = LoginHistory::query()
            ->where('user_id', $request->user()->id)
            ->latest('logged_in_at')
            ->paginate(15);

        return Inertia::render('Settings/LoginHistory', [
            'histories' => $histories,
        ]);
    }
    public function destroyOtherSessions(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        // Cierra todas las demás sesiones activas
        Auth::logoutOtherDevices($request->password);

        // Opcional: marcar los registros anteriores como cerrados
        $request->user()
            ->loginHistories()
            ->where('session_id', '!=', session()->getId())
            ->whereNull('logged_out_at')
            ->update(['logged_out_at' => now()]);

        return back()->with('success', 'Todas las otras sesiones han sido cerradas.');
    }
}
