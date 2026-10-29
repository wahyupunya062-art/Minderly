<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $notifikasiHeader = collect();
            $jumlahNotifikasiHeader = 0;

            if (auth()->check()) {
                $batasDeadline = today()->addDays(3);

                $query = DB::table('tugas')
                    ->where(
                        'id_pengguna',
                        auth()->user()->id_pengguna
                    )
                    ->where('status', '!=', 'Selesai')
                    ->where(function ($q) use ($batasDeadline) {
                        $q->where(function ($sub) use ($batasDeadline) {
                            $sub->whereNotNull('deadline')
                                ->whereDate(
                                    'deadline',
                                    '<=',
                                    $batasDeadline
                                );
                        })->orWhere('skor_prioritas', '>=', 80);
                    });

                $jumlahNotifikasiHeader = (clone $query)->count();

                $notifikasiHeader = $query
                    ->orderByRaw(
                        'CASE WHEN deadline IS NULL THEN 1 ELSE 0 END'
                    )
                    ->orderBy('deadline')
                    ->limit(5)
                    ->get();
            }

            $view->with([
                'notifikasiHeader' => $notifikasiHeader,
                'jumlahNotifikasiHeader' => $jumlahNotifikasiHeader,
            ]);
        });
    }
}
