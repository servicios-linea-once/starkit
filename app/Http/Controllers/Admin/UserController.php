<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Cloudinary\Cloudinary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    private Cloudinary $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => config('services.cloudinary.cloud_name'),
                'api_key'    => config('services.cloudinary.api_key'),
                'api_secret' => config('services.cloudinary.api_secret'),
            ],
            'url' => ['secure' => true],
        ]);
    }

    // ── Index ─────────────────────────────────────────────────────────
    public function index(Request $request): Response
    {
        $users = User::query()
            ->with('roles')
            ->when($request->search, fn ($q) =>
            $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%")
            )
            ->when($request->role, fn ($q) =>
            $q->whereHas('roles', fn ($r) => $r->where('name', $request->role))
            )
            ->when($request->status !== null && $request->status !== '', fn ($q) =>
            $q->where('is_active', (bool) $request->status)
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Users/Index', [
            'users'   => $users,
            'roles'   => Role::orderBy('name')->get(['id', 'name']),
            'filters' => $request->only(['search', 'role', 'status']),
        ]);
    }

    // ── Create ────────────────────────────────────────────────────────
    public function create(): Response
    {
        return Inertia::render('Admin/Users/Create', [
            'roles' => Role::orderBy('name')->get(['id', 'name']),
        ]);
    }

    // ── Store ─────────────────────────────────────────────────────────
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role'     => ['required', 'string', 'exists:roles,name'],
            'is_active'=> ['boolean'],
            'avatar'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $avatarUrl      = null;
        $avatarPublicId = null;

        if ($request->hasFile('avatar')) {
            $result = $this->cloudinary->uploadApi()->upload(
                $request->file('avatar')->getRealPath(),
                [
                    'folder'         => 'starkit/avatars',
                    'transformation' => [
                        'width'        => 200,
                        'height'       => 200,
                        'crop'         => 'fill',
                        'gravity'      => 'face',
                        'quality'      => 'auto',
                        'fetch_format' => 'auto',
                    ],
                ]
            );
            $avatarUrl      = $result['secure_url'];
            $avatarPublicId = $result['public_id'];
        }

        $user = User::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['password']),
            'is_active'         => $validated['is_active'] ?? true,
            'avatar_url'        => $avatarUrl,
            'avatar_public_id'  => $avatarPublicId,
            'email_verified_at' => now(),
        ]);

        $user->assignRole($validated['role']);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    // ── Edit ──────────────────────────────────────────────────────────
    public function edit(User $user): Response
    {
        return Inertia::render('Admin/Users/Edit', [
            'user'  => $user->load('roles'),
            'roles' => Role::orderBy('name')->get(['id', 'name']),
        ]);
    }

    // ── Update ────────────────────────────────────────────────────────
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'      => ['required', 'string', 'exists:roles,name'],
            'is_active' => ['boolean'],
            'avatar'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'password'  => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // Avatar nuevo
        if ($request->hasFile('avatar')) {
            if ($user->avatar_public_id) {
                $this->cloudinary->adminApi()->deleteAssets([$user->avatar_public_id]);
            }
            $result = $this->cloudinary->uploadApi()->upload(
                $request->file('avatar')->getRealPath(),
                [
                    'folder'         => 'starkit/avatars',
                    'transformation' => [
                        'width'        => 200,
                        'height'       => 200,
                        'crop'         => 'fill',
                        'gravity'      => 'face',
                        'quality'      => 'auto',
                        'fetch_format' => 'auto',
                    ],
                ]
            );
            $user->avatar_url       = $result['secure_url'];
            $user->avatar_public_id = $result['public_id'];
        }

        $user->name      = $validated['name'];
        $user->email     = $validated['email'];
        $user->is_active = $validated['is_active'] ?? $user->is_active;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();
        $user->syncRoles([$validated['role']]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    // ── Destroy ───────────────────────────────────────────────────────
    public function destroy(User $user): RedirectResponse
    {
        // No permitir eliminar al propio admin
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta desde aquí.');
        }

        if ($user->avatar_public_id) {
            $this->cloudinary->adminApi()->deleteAssets([$user->avatar_public_id]);
        }

        $user->delete();

        return back()->with('success', 'Usuario eliminado correctamente.');
    }

    // ── Toggle activo/inactivo ────────────────────────────────────────
    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes desactivar tu propia cuenta.');
        }

        $user->update(['is_active' => !$user->is_active]);

        return back()->with('success', $user->is_active
            ? 'Usuario activado.'
            : 'Usuario desactivado.'
        );
    }
}
