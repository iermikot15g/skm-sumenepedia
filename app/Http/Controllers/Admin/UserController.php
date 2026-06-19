<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UnitPelayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Filter search
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
        }
        
        // Filter role
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }
        
        $users = $query->with('unitPelayanan')
                       ->orderBy('name')
                       ->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = [
            'super_admin' => 'Super Admin',
            'admin_unit' => 'Admin Unit',
            'pimpinan_unit' => 'Pimpinan Unit',
            'pimpinan_utama' => 'Pimpinan Utama',
        ];
        
        $units = UnitPelayanan::where('is_active', true)->get();
        
        return view('admin.users.create', compact('roles', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin_unit,pimpinan_unit,pimpinan_utama',
            'unit_pelayanan_id' => 'nullable|exists:unit_pelayanan,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validasi: jika role admin_unit atau pimpinan_unit, unit_pelayanan_id wajib
        if (in_array($request->role, ['admin_unit', 'pimpinan_unit']) && !$request->unit_pelayanan_id) {
            return redirect()->back()
                ->withErrors(['unit_pelayanan_id' => 'Unit pelayanan wajib dipilih untuk role Admin Unit atau Pimpinan Unit.'])
                ->withInput();
        }

        // Validasi: jika role super_admin atau pimpinan_utama, unit_pelayanan_id harus null
        if (in_array($request->role, ['super_admin', 'pimpinan_utama']) && $request->unit_pelayanan_id) {
            return redirect()->back()
                ->withErrors(['unit_pelayanan_id' => 'Role Super Admin atau Pimpinan Utama tidak boleh memiliki unit pelayanan.'])
                ->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'unit_pelayanan_id' => $request->unit_pelayanan_id,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = [
            'super_admin' => 'Super Admin',
            'admin_unit' => 'Admin Unit',
            'pimpinan_unit' => 'Pimpinan Unit',
            'pimpinan_utama' => 'Pimpinan Utama',
        ];
        
        $units = UnitPelayanan::where('is_active', true)->get();
        
        return view('admin.users.edit', compact('user', 'roles', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin_unit,pimpinan_unit,pimpinan_utama',
            'unit_pelayanan_id' => 'nullable|exists:unit_pelayanan,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validasi: jika role admin_unit atau pimpinan_unit, unit_pelayanan_id wajib
        if (in_array($request->role, ['admin_unit', 'pimpinan_unit']) && !$request->unit_pelayanan_id) {
            return redirect()->back()
                ->withErrors(['unit_pelayanan_id' => 'Unit pelayanan wajib dipilih untuk role Admin Unit atau Pimpinan Unit.'])
                ->withInput();
        }

        // Validasi: jika role super_admin atau pimpinan_utama, unit_pelayanan_id harus null
        if (in_array($request->role, ['super_admin', 'pimpinan_utama']) && $request->unit_pelayanan_id) {
            return redirect()->back()
                ->withErrors(['unit_pelayanan_id' => 'Role Super Admin atau Pimpinan Utama tidak boleh memiliki unit pelayanan.'])
                ->withInput();
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'unit_pelayanan_id' => $request->unit_pelayanan_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Cegah menghapus diri sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}