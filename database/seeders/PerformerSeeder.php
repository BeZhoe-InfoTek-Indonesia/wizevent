<?php

namespace Database\Seeders;

use App\Models\Performer;
use App\Models\SettingComponent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerformerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $performers = [
            [
                'name' => 'Tulus',
                'description' => 'Award-winning Indonesian singer-songwriter known for his jazz-pop music and soulful voice.',
                'phone' => '+62 812 3456 7801',
                'type' => 'Music',
                'profession' => 'Singer',
                'is_active' => true,
            ],
            [
                'name' => 'Isyana Sarasvati',
                'description' => 'Multi-talented Indonesian singer, songwriter, and composer with a background in classical music.',
                'phone' => '+62 812 3456 7802',
                'type' => 'Music',
                'profession' => 'Singer',
                'is_active' => true,
            ],
            [
                'name' => 'Rizky Febian',
                'description' => 'Indonesian singer and actor, son of comedian Sule, known for pop and R&B music.',
                'phone' => '+62 812 3456 7803',
                'type' => 'Music',
                'profession' => 'Singer',
                'is_active' => true,
            ],
            [
                'name' => 'Kunto Aji',
                'description' => 'Indonesian singer-songwriter known for his folk-pop music and poetic lyrics.',
                'phone' => '+62 812 3456 7804',
                'type' => 'Music',
                'profession' => 'Singer',
                'is_active' => true,
            ],
            [
                'name' => 'Indra Lesmana',
                'description' => 'Legendary Indonesian jazz pianist, composer, and producer.',
                'phone' => '+62 812 3456 7805',
                'type' => 'Music',
                'profession' => 'Musician',
                'is_active' => true,
            ],
            [
                'name' => 'Raditya Dika',
                'description' => 'Indonesian stand-up comedian, writer, actor, and filmmaker.',
                'phone' => '+62 812 3456 7806',
                'type' => 'Comedy',
                'profession' => 'Comedian',
                'is_active' => true,
            ],
            [
                'name' => 'Ernest Prakasa',
                'description' => 'Indonesian stand-up comedian, actor, and film director.',
                'phone' => '+62 812 3456 7807',
                'type' => 'Comedy',
                'profession' => 'Comedian',
                'is_active' => true,
            ],
            [
                'name' => 'Bebi Romeo',
                'description' => 'Indonesian musician and producer, former vocalist of Sheila on 7.',
                'phone' => '+62 812 3456 7808',
                'type' => 'Music',
                'profession' => 'Musician',
                'is_active' => true,
            ],
            [
                'name' => 'Sandhy Sondoro',
                'description' => 'Indonesian singer-songwriter known for his soul and jazz music.',
                'phone' => '+62 812 3456 7809',
                'type' => 'Music',
                'profession' => 'Singer',
                'is_active' => true,
            ],
            [
                'name' => 'Afgan Syahreza',
                'description' => 'Indonesian singer and actor known for his R&B and pop music.',
                'phone' => '+62 812 3456 7810',
                'type' => 'Music',
                'profession' => 'Singer',
                'is_active' => true,
            ],
        ];

        $userId = DB::table('users')->where('email', 'admin@example.com')->value('id');

        $performerTypes = SettingComponent::whereHas('setting', fn ($q) => $q->where('key', 'performer_types'))->pluck('name', 'id');
        $performerProfessions = SettingComponent::whereHas('setting', fn ($q) => $q->where('key', 'performer_professions'))->pluck('name', 'id');

        foreach ($performers as $performer) {
            $typeId = $performerTypes->search($performer['type']);
            $professionId = $performerProfessions->search($performer['profession']);

            Performer::create([
                'name' => $performer['name'],
                'description' => $performer['description'],
                'phone' => $performer['phone'],
                'type_setting_component_id' => $typeId,
                'profession_setting_component_id' => $professionId,
                'is_active' => $performer['is_active'],
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
        }
    }
}
