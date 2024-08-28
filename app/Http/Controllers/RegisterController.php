<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    public function register()
    {
        return view('register.register');
    }

    public function actionregister(Request $request)
    {
        $result = [];

        // Start database transaction
        DB::beginTransaction();

        try {
            // Validate the request data
            $validatedData = $request->validate([
                'email' => 'required|email|unique:users',
                'name' => 'required|string|max:255',
                'password' => 'required|string|min:8',
                'role' => 'required|string|max:255',
            ]);

            // Create the user
            $user = User::create([
                'email' => $request->email,
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'roles' => $request->role,
            ]);

            // Commit the transaction
            DB::commit();
            $result['pesan'] = 'Register Berhasil. Akun Anda sudah Aktif.';
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Rollback the transaction in case of validation error
            DB::rollback();
            $result['pesan'] = 'Validation Error: ' . implode(', ', Arr::flatten($e->errors()));
        } catch (\Exception $e) {
            // Rollback the transaction in case of general error
            DB::rollback();
            $result['pesan'] = 'Error: ' . $e->getMessage();
        }
        // Return the result as JSON
        return response()->json($result);
    }

    public function editregister(){
            // Fetch user data
            $user = User::find(session('id')); // Assuming session has user id
            return view('register.editregister', compact('user'));
    }

    public function updateregister(Request $request)
    {
        $result = [];
        DB::beginTransaction();
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'email' => 'required|email',
                'name' => 'required|string|max:255',
                'password' => 'nullable|string|min:8',
                'roles' => 'required|string|max:255',
            ]);

            $user = User::find(session('id'));

            // Update user details
            $user->email = $request->email;
            $user->name = $request->name;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->roles = $request->roles;
            $user->save();

            DB::commit();
            $result['pesan'] = 'Update Berhasil.';
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            $result['pesan'] = 'Validation Error: ' . implode(', ', Arr::flatten($e->errors()));
        } catch (\Exception $e) {
            DB::rollback();
            $result['pesan'] = 'Error: ' . $e->getMessage();
        }
        return response()->json($result);
    }

    public function listregister()
    {
        return view('register.listregister');
    }

    public function filter_register(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Silakan login terlebih dahulu'], 401);
        }

        $user = Auth::user();

        $allowedRoles = ['programmer', 'admin'];
        if (!in_array($user->roles, $allowedRoles)) {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        // Jika pengguna memiliki peran yang sesuai, lanjutkan dengan query
        $query = DB::table('users');

        if ($request->has('startDate') && $request->startDate) {
            $query->where('created_at', '>=', $request->startDate);
        }

        if ($request->has('endDate') && $request->endDate) {
            $query->where('created_at', '<=', $request->endDate);
        }

        if ($request->has('searchText') && $request->searchText) {
            $query->where(function($q) use ($request) {
                $q->where('email', 'like', '%' . $request->searchText . '%')
                  ->orWhere('name', 'like', '%' . $request->searchText . '%')
                  ->orWhere('roles', 'like', '%' . $request->searchText . '%');
            });
        }

        $users = $query->get();

        return response()->json([
            'data' => $users
        ]);
    }


    public function edit_list_register($id){
        $user = User::find($id);
        return response()->json($user);
    }

    public function update_list_register(Request $request)
    {
        $result = [];
        DB::beginTransaction();
        try {
            $validatedData = $request->validate([
                'email' => 'required|email',
                'name' => 'required|string|max:255',
                'password' => 'nullable|string|min:8',
                'roles' => 'required|string|max:255',
            ]);

            $id = $request->input('id');
            $user = User::find($id);

            $user->email = $request->email;
            $user->name = $request->name;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->roles = $request->roles;
            $user->save();

            DB::commit();
            $result['pesan'] = 'Update Berhasil.';
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            $result['pesan'] = 'Validation Error: ' . implode(', ', Arr::flatten($e->errors()));
        } catch (\Exception $e) {
            DB::rollback();
            $result['pesan'] = 'Error: ' . $e->getMessage();
        }
        return response()->json($result);
    }

    public function delete_list_register($id){
        $user = User::find($id);

        if ($user){
            $user->delete();
            return response()->json(['success' => 'User berhasil dihapus']);
        }else{
            return response()->json(['error' => 'User tidak ditemukan'], 404);
        }
    }

}
