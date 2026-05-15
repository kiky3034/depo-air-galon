<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session()->has('id_user')) {
            return $this->redirectByRole(session('role'));
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)
                     ->where('password', $request->password) // plain text
                     ->first();

        if (!$user) {
            return back()->with('error', 'Username atau Password salah!')->withInput();
        }

        // Set session (mirip project asli)
        session([
            'id_user' => $user->id_user,
            'username' => $user->username,
            'nama' => $user->nama,
            'role' => $user->role,
            'id_pelanggan' => $user->id_pelanggan,
        ]);

        return $this->redirectByRole($user->role);
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('login')->with('success', 'Berhasil logout!');
    }

    /**
     * Register pelanggan dengan kode aktivasi (dari tabel pelanggan yg sudah ada)
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'kode_pelanggan' => 'required',
            'nama' => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:3',
        ]);

        // Cek kode pelanggan
        $pelanggan = Pelanggan::where('kode_pelanggan', $request->kode_pelanggan)->first();

        if (!$pelanggan) {
            return back()->with('error', 'Kode Pelanggan tidak ditemukan! Hubungi petugas.')->withInput();
        }

        // Cek apakah pelanggan sudah punya akun
        $existing = User::where('id_pelanggan', $pelanggan->id_pelanggan)->first();
        if ($existing) {
            return back()->with('error', 'Kode Pelanggan ini sudah terdaftar!')->withInput();
        }

        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => $request->password,
            'role' => 'pelanggan',
            'id_pelanggan' => $pelanggan->id_pelanggan,
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    /**
     * Register pelanggan mandiri (tanpa kode, auto-generate)
     */
    public function showRegisterMandiri()
    {
        return view('auth.register_mandiri');
    }

    public function registerMandiri(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:3',
        ]);

        // Auto generate kode pelanggan
        $count = Pelanggan::count();
        if ($count == 0) {
            $id_baru = 1;
        } else {
            $id_baru = Pelanggan::max('id_pelanggan') + 1;
        }
        $kode = 'PLG' . $id_baru;

        $pelanggan = Pelanggan::create([
            'kode_pelanggan' => $kode,
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => $request->password,
            'role' => 'pelanggan',
            'no_telp' => $request->no_hp,
            'alamat' => $request->alamat,
            'id_pelanggan' => $pelanggan->id_pelanggan,
        ]);

        return redirect()->route('login')->with('success', "Registrasi berhasil! Kode Pelanggan Anda: $kode");
    }

    private function redirectByRole($role)
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'pemilik' => redirect()->route('pemilik.dashboard'),
            'pegawai' => redirect()->route('pegawai.dashboard'),
            'pelanggan' => redirect()->route('pelanggan.dashboard'),
            default => redirect()->route('login'),
        };
    }
}
