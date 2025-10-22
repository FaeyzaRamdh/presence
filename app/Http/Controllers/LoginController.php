<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
/**
 * Display a listing of the resource.
 */

public function loginAuth(Request $request)
    {
        // validasi
        $request->validate([
            // name_input ->"validasi"
            'email' => 'required',
            'password' => 'required'
        ], [
            'email.required' => 'Email wajib di isi',
            'password.required' => 'Password wajib di isi'
        ]);

        // menyimpan data yang akan di verivikasi
        $data = $request->only(['email', 'password']);
        // Auth::attempt($data) : mengecek data di tabel users
        if (Auth::attempt($data)) {
            // setelah berhasil login, dicek lagi terkait role nya untuk menentukan perpindahan halaman
            if (Auth::user()->role == 'admin') {
                return redirect()->route('dashboard')->with('success', 'Berhasil Login!');
            } elseif (Auth::user()->role == 'user') {
                return redirect()->route('user.home')->with('success', 'Berhasil Login!');
            }
        } else {
            return redirect()->back()->with('error', 'Login gagal, Pastikan email dan password benar');
        }
    }
    public function logout()
    {
        // auth::logout() : menghapus session login
        Auth::logout();
        return redirect()->route('login')->with('logout', 'Logout berhasil, silahkan login kembali untuk mengakses lebih lengkap');
    }



public function index()
{
    return view('admin.auth.login');
}

/**
 * Show the form for creating a new resource.
 */
public function create()
{
    //
}

/**
 * Store a newly created resource in storage.
 */
public function store(Request $request)
{
//
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
