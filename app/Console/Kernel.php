<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Daftarkan jadwal perintah Artisan di sini.
     */
    protected function schedule(Schedule $schedule): void
    {
        // DEMO: Pastikan semua transaksi baru punya pay_url otomatis.
        // Jalankan seeder setiap menit. Aman karena hanya mengisi record yang masih kosong.
        $schedule->command('db:seed --class=Database\\Seeders\\SetPayUrlForDemoTransactionSeeder')
                 ->everyMinute();

        // Tambahkan jadwal lain di bawah ini bila perlu.
        // Contoh:
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Daftarkan commands kustom aplikasi & routes console.
     */
    protected function commands(): void
    {
        // Autoload semua command di app/Console/Commands
        $this->load(__DIR__.'/Commands');

        // Route-based console commands
        require base_path('routes/console.php');
    }
}
