<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class OwnerSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('OWNER_EMAIL', 'yudha@mandalacare.com');
        $password = env('OWNER_PASSWORD', 'GANTI_PASSWORD_INI_SEBELUM_SEED');
        $name = env('OWNER_NAME', 'Yudha Tama');

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
            ]
        );

        $this->command->info("Akun pemilik klinik berhasil dibuat/diperbarui: {$email}");
    }
}