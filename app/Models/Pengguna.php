<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;

class Pengguna extends Authenticatable implements CanResetPasswordContract
{
    use Notifiable;
    use CanResetPasswordTrait;

    protected $table = 'pengguna';

    protected $primaryKey = 'id_pengguna';

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'foto',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'dibuat_pada' => 'datetime',
    ];

    /**
     * Kolom password untuk autentikasi.
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Relasi profil pengguna.
     */
    public function profil()
    {
        return $this->hasOne(
            ProfilPengguna::class,
            'id_pengguna',
            'id_pengguna'
        );
    }
}
