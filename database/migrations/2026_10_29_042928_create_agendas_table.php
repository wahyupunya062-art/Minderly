
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agenda', function (Blueprint $table) {
            $table->id();
            $table->integer('id_pengguna')->index();

            $table->string('nama_agenda', 150);
            $table->string('jenis', 20); // rutin atau tambahan

            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();

            $table->json('hari_rutin')->nullable();

            $table->time('jam_mulai');
            $table->time('jam_selesai');

            $table->string('lokasi')->nullable();
            $table->text('deskripsi')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agenda');
    }
};
