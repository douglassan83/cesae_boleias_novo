<?php
// database/seeders/UsersSeeder.php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin CESAE',
                'email' => 'admin@msft.cesae.pt',
                'password' => Hash::make('admin@msft.cesae.pt'),
                'role' => 'admin',
                'pickup_location' => 'São João da Madeira',
                'whatsapp_phone' => '+351 912345678',
                'bio' => 'Admin CESAE Boleias - Gerir tudo'
            ],
            [
                'name' => 'Motorista',
                'email' => 'motorista@msft.cesae.pt',
                'password' => Hash::make('motorista@msft.cesae.pt'),
                'role' => 'driver',
                'pickup_location' => 'Vale de Cambra',
                'whatsapp_phone' => '+351 923456789',
                'bio' => 'Motorista disponível SJ Madeira → CESAE'
            ],
            [
                'name' => 'Passageiro',
                'email' => 'passageiro@msft.cesae.pt',
                'password' => Hash::make('passageiro@msft.cesae.pt'),
                'role' => 'passenger',
                'pickup_location' => 'Oliveira de Azeméis',
                'whatsapp_phone' => '+351 934567890',
                'bio' => 'Estudante CESAE - preciso boleia CESAE'
            ]
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
