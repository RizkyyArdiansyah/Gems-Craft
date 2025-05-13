<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable; 
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, CanResetPassword;

    protected $fillable = [
        'name', 'email', 'password', 'email_verified', 'profile_picture', 'is_admin'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified' => 'boolean',
    ];

    // Cek apakah email sudah terverifikasi
    public function hasVerifiedEmail()
    {
        return $this->email_verified;
    }

    // Tandai email sebagai verified
    public function markEmailAsVerified()
    {
        return $this->update(['email_verified' => true]);
    }
    

public function sendEmailVerificationNotification()
{
    $this->notify(new VerifyEmail);
}

}
