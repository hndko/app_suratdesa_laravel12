<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->dataTable($request);
        }

        $data = [
            'title' => 'Manajemen Role & Permission',
            'totalRoles' => Role::count(),
            'totalPermissions' => Permission::count(),
            'coreRoles' => Role::whereIn('name', ['super-admin', 'kades', 'operator'])->count(),
            'customRoles' => Role::whereNotIn('name', ['super-admin', 'kades', 'operator'])->count(),
        ];

        return view('backend.role.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Role',
            'permissionGroups' => $this->permissionGroups(),
        ];

        return view('backend.role.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($role)
            ->event('created')
            ->withProperties(['permissions' => $request->permissions ?? []])
            ->log('role created');

        return redirect()->route('role.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $data = [
            'title' => 'Detail Role: ' . $role->name,
            'role' => $role,
            'permissionGroups' => $this->permissionGroups($role->permissions),
        ];

        return view('backend.role.show', $data);
    }

    public function edit(string $id)
    {
        $role = Role::findOrFail($id);

        if ($role->name === 'super-admin') {
            return redirect()->route('role.index')->with('error', 'Role Super Admin tidak dapat diubah karena memiliki akses penuh secara otomatis.');
        }

        $data = [
            'title' => 'Edit Role',
            'role' => $role,
            'permissionGroups' => $this->permissionGroups(),
        ];

        return view('backend.role.edit', $data);
    }

    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        if ($role->name === 'super-admin') {
            return redirect()->route('role.index')->with('error', 'Role Super Admin tidak dapat diubah.');
        }

        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->name = $request->name;
        $role->save();

        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->syncPermissions([]);
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($role)
            ->event('updated')
            ->withProperties(['permissions' => $request->permissions ?? []])
            ->log('role updated');

        return redirect()->route('role.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);

        // Prevent deleting core roles
        if (in_array($role->name, ['super-admin', 'kades', 'operator'])) {
            return back()->with('error', 'Role inti sistem tidak dapat dihapus.');
        }

        $role->delete();

        activity()
            ->causedBy(auth()->user())
            ->event('deleted')
            ->withProperties(['role' => $role->name])
            ->log('role deleted');

        return redirect()->route('role.index')->with('success', 'Role berhasil dihapus.');
    }

    private function dataTable(Request $request): JsonResponse
    {
        $columns = [
            0 => 'id',
            1 => 'name',
            2 => 'permissions_count',
            3 => 'users_count',
            4 => 'created_at',
        ];

        $baseQuery = Role::query()->withCount(['permissions', 'users']);
        $recordsTotal = (clone $baseQuery)->count();

        $search = trim((string) $request->input('search.value'));
        if ($search !== '') {
            $baseQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('permissions', function ($permissionQuery) use ($search) {
                        $permissionQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();
        $orderColumnIndex = (int) $request->input('order.0.column', 4);
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

        $canShow = $request->user()?->can('role-show') ?? false;
        $canEdit = $request->user()?->can('role-edit') ?? false;
        $canDestroy = $request->user()?->can('role-destroy') ?? false;
        $coreRoles = ['super-admin', 'kades', 'operator'];

        $data = $rows->map(function (Role $role, int $index) use ($start, $canShow, $canEdit, $canDestroy, $coreRoles) {
            $isCore = in_array($role->name, $coreRoles, true);
            $actions = '<div class="action-group">';

            if ($canShow) {
                $actions .= '<a href="' . route('role.show', $role->id) . '" class="btn btn-sm btn-primary" title="Detail Role"><i class="fas fa-eye"></i></a>';
            }

            if ($canEdit && $role->name !== 'super-admin') {
                $actions .= '<a href="' . route('role.edit', $role->id) . '" class="btn btn-sm btn-warning" title="Edit Role"><i class="fas fa-edit"></i></a>';
            }

            if ($canDestroy && !$isCore) {
                $actions .= '<form action="' . route('role.destroy', $role->id) . '" method="POST" class="d-inline js-role-confirm" data-confirm-text="Yakin ingin menghapus role ' . e($role->name) . '?">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="btn btn-sm btn-danger" title="Hapus Role"><i class="fas fa-trash"></i></button>'
                    . '</form>';
            }

            $actions .= '</div>';

            return [
                'no' => $start + $index + 1,
                'name' => '<div class="role-cell"><span class="role-avatar"><i class="fas fa-user-shield"></i></span><div><strong>' . e($role->name) . '</strong><small>' . ($isCore ? 'Role inti sistem' : 'Role custom') . '</small></div></div>',
                'permissions' => '<span class="permission-pill"><i class="fas fa-key"></i>' . number_format((int) $role->permissions_count, 0, ',', '.') . ' Permission</span>',
                'users' => '<span class="user-pill"><i class="fas fa-users"></i>' . number_format((int) $role->users_count, 0, ',', '.') . ' User</span>',
                'status' => $isCore
                    ? '<span class="status-pill status-info"><i class="fas fa-lock"></i> Protected</span>'
                    : '<span class="status-pill status-success"><i class="fas fa-check-circle"></i> Custom</span>',
                'created_at' => '<strong>' . e($role->created_at?->format('d/m/Y')) . '</strong><small>' . e($role->created_at?->format('H:i')) . '</small>',
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

    private function permissionGroups(?Collection $selectedPermissions = null): Collection
    {
        $selected = $selectedPermissions?->pluck('name')->all() ?? [];

        return Permission::orderBy('name')->get()->groupBy(function (Permission $permission) {
            return str($permission->name)->before('-')->headline()->toString();
        })->map(function (Collection $permissions, string $groupName) use ($selected) {
            return [
                'name' => $groupName,
                'permissions' => $permissions->map(function (Permission $permission) use ($selected) {
                    return [
                        'id' => $permission->id,
                        'name' => $permission->name,
                        'checked' => in_array($permission->name, $selected, true),
                    ];
                }),
            ];
        });
    }
}
