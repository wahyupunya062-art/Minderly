<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agenda extends Model
{
    protected $table = 'agenda';

    protected $fillable = [
        'id_pengguna',
        'nama_agenda',
        'jenis',
        'tanggal_mulai',
        'tanggal_selesai',
        'hari_rutin',
        'jam_mulai',
        'jam_selesai',
        'lokasi',
        'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'hari_rutin' => 'array',
        ];
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(
            Pengguna::class,
            'id_pengguna',
            'id_pengguna'
        );
    }
}
