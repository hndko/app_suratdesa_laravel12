<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Manajemen Role & Permission',
            'roles' => Role::with('permissions')->get()
        ];
        return view('backend.role.index', $data);
    }

    public function create()
    {
        return view('backend.role.create', [
            'title' => 'Tambah Role',
            'permissions' => Permission::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create(['name' => $request->name]);
        
        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('role.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $data = [
            'title' => 'Detail Role: ' . $role->name,
            'role' => $role
        ];
        return view('backend.role.show', $data);
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        if ($role->name === 'super-admin') {
            return redirect()->route('role.index')->with('error', 'Role Super Admin tidak dapat diubah karena memiliki akses penuh secara otomatis.');
        }

        return view('backend.role.edit', [
            'title' => 'Edit Role',
            'role' => $role,
            'permissions' => Permission::all()
        ]);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        if ($role->name === 'super-admin') {
            return redirect()->route('role.index')->with('error', 'Role Super Admin tidak dapat diubah.');
        }

        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
        ]);

        $role->name = $request->name;
        $role->save();

        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('role.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        
        // Prevent deleting core roles
        if (in_array($role->name, ['super-admin', 'kades', 'operator'])) {
            return back()->with('error', 'Role inti sistem tidak dapat dihapus.');
        }

        $role->delete();
        return redirect()->route('role.index')->with('success', 'Role berhasil dihapus.');
    }
}
