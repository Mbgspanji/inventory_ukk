<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function export() {
        return Excel::download(new UsersExport, 'users_list.xlsx');
    }

    public function index(Request $request) {
        $users = User::query()
            ->when($request->name, function ($query, $name) {
                $query->where('name', 'like', "%{$name}%");
            })
            ->when($request->role, function ($query, $role) {
                $query->where('role', $role);
            })
            ->get();

        return view('users.index', compact('users'));
    }

    public function create() {
        return view('users.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role'  => 'required|in:admin,operator'
        ]);

        // Ambil prefix dari nama/email
        $prefix = strtolower(substr($request->name, 0, 4));
        if (strlen($prefix) < 4) {
            $prefix = strtolower(substr($request->email, 0, 4));
        }

        // Hindari error kalau table kosong
        $nextId = (User::max('id') ?? 0) + 1;

        // Generate password unik
        $generatedPassword = $prefix . $nextId;

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make($generatedPassword)
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User created.')
            ->with('generated_password', "Password untuk {$request->email} adalah: $generatedPassword");
    }

    public function edit(User $user) {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user) {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'role'         => 'required|in:admin,operator',
            'new_password' => 'nullable|min:6'
        ]);

        $data = $request->only(['name', 'email', 'role']);

        if ($request->filled('new_password')) {
            $data['password'] = Hash::make($request->new_password);
            $data['is_password_updated'] = true;
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user) {
        if ($user->id === auth()->$user()) {
            return back()->with('error', 'Cannot delete yourself.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted.');
    }
}