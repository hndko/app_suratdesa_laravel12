<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Jobs\SendWhatsAppMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->dataTable($request);
        }

        $data = [
            'title' => 'Manajemen User',
            'totalUsers' => User::count(),
            'totalRoles' => Role::count(),
            'totalWithPhone' => User::whereNotNull('phone')->where('phone', '!=', '')->count(),
            'latestUsers' => User::whereDate('created_at', '>=', now()->subDays(30))->count(),
            'roles' => Role::orderBy('name')->get(['id', 'name']),
        ];

        return view('backend.user.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah User',
            'roles' => Role::orderBy('name')->get(),
        ];

        return view('backend.user.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
        ]);

        $user->assignRole($request->role);

        // WhatsApp Notification
        if ($user->phone) {
            $siteName = \App\Facades\Setting::get('site_name', 'SIMADES');
            $message = "Halo {$user->name}, akun Anda di {$siteName} telah berhasil dibuat.\n\nEmail: {$user->email}\nRole: {$request->role}\n\nSilakan login ke sistem untuk mulai bertugas. Terima kasih.";
            SendWhatsAppMessage::dispatch($user->phone, $message);
        }

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        $data = [
            'title' => 'Edit User',
            'user' => $user,
            'roles' => Role::orderBy('name')->get(),
        ];

        return view('backend.user.edit', $data);
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->password) {
            $user->password = $request->password;
        }

        $user->save();

        $user->syncRoles([$request->role]);

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus diri sendiri.');
        }

        $user->delete();
        return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
    }

    private function dataTable(Request $request): JsonResponse
    {
        $columns = [
            0 => 'id',
            1 => 'name',
            2 => 'email',
            5 => 'created_at',
        ];

        $baseQuery = User::query()->with('roles:id,name');
        $recordsTotal = (clone $baseQuery)->count();

        if ($request->filled('role')) {
            $baseQuery->role($request->input('role'));
        }

        $search = trim((string) $request->input('search.value'));
        if ($search !== '') {
            $baseQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhereHas('roles', function ($roleQuery) use ($search) {
                        $roleQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();
        $orderColumnIndex = (int) $request->input('order.0.column', 5);
        $orderColumn = $columns[$orderColumnIndex] ?? 'created_at';
        $orderDirection = $request->input('order.0.dir') === 'asc' ? 'asc' : 'desc';
        $length = (int) $request->input('length', 10);
        $start = max((int) $request->input('start', 0), 0);
        $length = $length > 0 ? min($length, 100) : 10;

        $rows = $baseQuery
            ->orderBy($orderColumn, $orderDirection)
            ->skip($start)
            ->take($length)
            ->get();

        $canEdit = $request->user()?->can('user-edit') ?? false;
        $canDestroy = $request->user()?->can('user-destroy') ?? false;
        $currentUserId = $request->user()?->id;

        $data = $rows->map(function (User $user, int $index) use ($start, $canEdit, $canDestroy, $currentUserId) {
            $roles = $user->roles->map(function ($role) {
                return '<span class="role-pill">' . e($role->name) . '</span>';
            })->implode(' ');

            $actions = '<div class="action-group">';

            if ($canEdit) {
                $actions .= '<a href="' . route('user.edit', $user->id) . '" class="btn btn-sm btn-warning" title="Edit User"><i class="fas fa-edit"></i></a>';
            }

            if ($canDestroy) {
                $disabled = $user->id === $currentUserId ? ' disabled' : '';
                $actions .= '<form action="' . route('user.destroy', $user->id) . '" method="POST" class="d-inline js-user-confirm" data-confirm-text="Yakin ingin menghapus user ' . e($user->name) . '?">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="btn btn-sm btn-danger" title="Hapus User"' . $disabled . '><i class="fas fa-trash"></i></button>'
                    . '</form>';
            }

            $actions .= '</div>';

            return [
                'no' => $start + $index + 1,
                'name' => '<div class="user-cell"><span class="user-avatar">' . e(Str::upper(Str::substr($user->name, 0, 1))) . '</span><div><strong>' . e($user->name) . '</strong><small>ID User #' . e($user->id) . '</small></div></div>',
                'email' => '<span>' . e($user->email) . '</span><small>' . e($user->phone ?: 'Nomor WhatsApp belum diisi') . '</small>',
                'roles' => $roles ?: '<span class="role-pill role-empty">Belum ada role</span>',
                'status' => $user->id === $currentUserId
                    ? '<span class="status-pill status-info"><i class="fas fa-user-check"></i> Akun Anda</span>'
                    : '<span class="status-pill status-success"><i class="fas fa-check-circle"></i> Aktif</span>',
                'created_at' => '<strong>' . e($user->created_at?->format('d/m/Y')) . '</strong><small>' . e($user->created_at?->format('H:i')) . '</small>',
                'aksi' => $actions,
            ];
        });

        return response()->json([
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }
}
