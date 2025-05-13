<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; 


class AuthController extends Controller
{
    // Tampilkan form login (redirect jika sudah login)
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function showHomePage() 
    {
        $products = Product::latest()->take(8)->get();

        return view('home', compact ('products'));
    }

    // Proses login
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (Auth::attempt($request->only('email', 'password'))) {
        $user = Auth::user();

        // Cek apakah user sudah verifikasi email
        if (!$user->email_verified) {
            Auth::logout();
            return redirect()->route('login')
                ->with('email_not_verified', true)
                ->with('email_address', $request->email);
        }

        // Cek apakah user adalah admin
        if ($user->is_admin) {
            return redirect()->route('dashboard')->with('success', 'Welcome Admin, ' . $user->name);
        }

        return redirect()->route('home')->with('success', 'Login Success! Welcome, ' . $user->name);
    }

    return back()->withErrors(['email' => 'Email atau password salah']);
}



    // Logout user
    public function logout(Request $request)
{
    if (!Auth::check()) {
        return redirect()->route('login')->withErrors(['error' => 'Anda belum login!']);
    }

    Auth::logout();
    session()->flush();

    return redirect()->route('login')->with('success', 'Logout berhasil!');
}


    // Tampilkan form register (redirect jika sudah login)
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    // Proses register

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|confirmed',
        ], [
            'email.unique' => 'Email sudah terdaftar, silakan gunakan email lain.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        event(new Registered($user)); // Kirim email verifikasi otomatis
    
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan cek email untuk verifikasi.');
    }
    
public function verify($id)
    {
        $user = User::findOrFail($id);

        if ($user->email_verified) {
            return redirect('/home')->with('status', 'Email sudah diverifikasi.');
        }

        $user->markEmailAsVerified(); // Set email_verified menjadi true

        return redirect('/login')->with('success', 'Email berhasil diverifikasi!');
    }
    public function resendVerification(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        if ($user->email_verified) {
            return redirect('/login')->with('status', 'Email sudah terverifikasi.');
        }

        event(new Registered($user)); // Kirim ulang email verifikasi

        return back()->with('success', 'Link verifikasi telah dikirim ulang.');
    }
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    // Menampilkan halaman reset password
    public function showResetForm(string $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    // Proses reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:5|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Password berhasil direset!')
            : back()->withErrors(['email' => 'Gagal mereset password.']);
    }

    public function showSetting()
    {
        if (Auth::check()) {
            return view('auth.profile-update'); // Pastikan file ini ada
        }
        return redirect()->route('login');
    }


public function updateProfilePicture(Request $request)
{
    $request->validate([
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $user = Auth::user();

    if ($request->hasFile('profile_picture')) {
        // Hapus foto lama jika ada
        if (!empty($user->profile_picture)) {
            Storage::delete($user->profile_picture);
        }

        // Simpan foto baru
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');

    DB::table('users')
    ->where('id', $user->id)
    ->update(['profile_picture' => $path]); // Hanya simpan 'profile_pictures/xxx.jpg'

    }

    return redirect()->back()->with('success_profile', 'Foto profil berhasil diperbarui.');
}



public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|string|min:6|confirmed',
    ], [
        'current_password.required' => 'Password lama wajib diisi.',
        'new_password.required' => 'Password baru wajib diisi.',
        'new_password.min' => 'Password baru minimal 6 karakter.',
        'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
    ]);

    $user = Auth::user();

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Password lama tidak cocok.'])->withInput();
    }

    DB::table('users')
        ->where('id', $user->id)
        ->update(['password' => Hash::make($request->new_password)]);

    return redirect()->back()->with('success_password', 'Password berhasil diperbarui.');
}


}
