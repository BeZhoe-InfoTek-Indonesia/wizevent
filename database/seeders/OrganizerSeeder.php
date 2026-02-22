<?php

namespace Database\Seeders;

use App\Models\Organizer;
use Illuminate\Database\Seeder;

class OrganizerSeeder extends Seeder
{
    public function run(): void
    {
        $organizers = [
            [
                'name' => 'EventPro Inc.',
                'description' => 'Leading event management company specializing in corporate events and conferences.',
                'email' => 'info@eventpro.com',
                'phone' => '+1 234 567 8900',
                'website' => 'https://eventpro.com',
                'social_media' => [
                    'facebook' => 'https://facebook.com/eventpro',
                    'twitter' => 'https://twitter.com/eventpro',
                    'instagram' => 'https://instagram.com/eventpro',
                ],
                'address' => '123 Business Avenue, Suite 400, New York, NY 10001, USA',
            ],
            [
                'name' => 'Creative Arts Productions',
                'description' => 'Award-winning production company for music festivals, concerts, and cultural events.',
                'email' => 'contact@creativearts.com',
                'phone' => '+1 345 678 9012',
                'website' => 'https://creativearts.com',
                'social_media' => [
                    'facebook' => 'https://facebook.com/creativearts',
                    'instagram' => 'https://instagram.com/creativearts',
                    'youtube' => 'https://youtube.com/@creativearts',
                ],
                'address' => '456 Arts District, Los Angeles, CA 90012, USA',
            ],
            [
                'name' => 'Tech Summit Asia',
                'description' => 'Premier technology conference organizer across Southeast Asia.',
                'email' => 'hello@techsummit.asia',
                'phone' => '+62 812 3456 7890',
                'website' => 'https://techsummit.asia',
                'social_media' => [
                    'linkedin' => 'https://linkedin.com/company/techsummit',
                    'twitter' => 'https://twitter.com/techsummitasia',
                ],
                'address' => 'Jalan Sudirman No. 78, Jakarta Pusat, DKI Jakarta 10220, Indonesia',
            ],
            [
                'name' => 'Sports Events International',
                'description' => 'Specializes in organizing sports tournaments, championships, and athletic events.',
                'email' => 'sports@sportsevents.com',
                'phone' => '+44 20 7123 4567',
                'website' => 'https://sportsevents.com',
                'social_media' => [
                    'facebook' => 'https://facebook.com/sportsevents',
                    'instagram' => 'https://instagram.com/sportsevents',
                    'twitter' => 'https://twitter.com/sportsevents',
                ],
                'address' => '100 Stadium Road, London EC1A 1BB, United Kingdom',
            ],
            [
                'name' => 'Community Builders',
                'description' => 'Non-profit organization focused on community events, workshops, and cultural festivals.',
                'email' => 'community@cb.org',
                'phone' => '+61 2 9876 5432',
                'website' => 'https://communitybuilders.org',
                'social_media' => [
                    'facebook' => 'https://facebook.com/communitybuilders',
                    'instagram' => 'https://instagram.com/communitybuilders',
                ],
                'address' => '789 Community Lane, Sydney NSW 2000, Australia',
            ],
        ];

        foreach ($organizers as $organizer) {
            Organizer::create([
                'name' => $organizer['name'],
                'description' => $organizer['description'],
                'email' => $organizer['email'],
                'phone' => $organizer['phone'],
                'website' => $organizer['website'],
                'social_media' => $organizer['social_media'],
                'address' => $organizer['address'],
                'created_by' => 1,
                'updated_by' => 1,
            ]);
        }
    }
}
