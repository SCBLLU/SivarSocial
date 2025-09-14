<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'title' => '¿Quieres probar la función de mensajes?',
                'content' => 'Al enviar un mensaje a tus amigos en SivarSocial aceptas recibir mensajes de su parte también.',
                'type' => Banner::TYPE_FEATURE,
                'action_text' => 'Mensajería',
                'action_url' => '/chatify',
                'is_active' => true,
                'start_date' => now(),
                'end_date' => now()->addDays(30),
            ],
            [
                'title' => 'Nuevo sistema de notificaciones',
                'content' => 'Mantente al día con todas las interacciones en tiempo real. Recibe notificaciones instantáneas de likes, comentarios y nuevos seguidores.',
                'type' => Banner::TYPE_UPDATE,
                'action_text' => 'Notificaciones',
                'action_url' => '/',
                'is_active' => true,
                'start_date' => now(),
                'end_date' => now()->addDays(20),
            ],
            [
                'title' => 'Mantenimiento programado',
                'content' => 'Notificare que habrá un mantenimiento programado el próximo Lunes de 2 AM a 8 AM. Durante este tiempo, el sitio podría no estar disponible. Gracias por su comprensión.',
                'type' => Banner::TYPE_INFO,
                'action_text' => 'Enterado',
                'action_url' => '/',
                'is_active' => true,
                'start_date' => now(),
                'end_date' => now()->addDays(35),
            ],
        ];

        foreach ($banners as $bannerData) {
            Banner::create($bannerData);
        }
    }
}
