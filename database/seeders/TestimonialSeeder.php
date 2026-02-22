<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('email', '!=', 'admin@example.com')->limit(25)->get();

        if ($users->isEmpty()) {
            $this->command->error('No users found. Please run UserSeeder first.');

            return;
        }

        $events = Event::published()->limit(10)->get();

        if ($events->isEmpty()) {
            $this->command->error('No published events found. Please run EventSeeder first.');

            return;
        }

        Testimonial::query()->delete();

        $testimonials = [
            ['content' => 'Absolutely amazing experience! The event was well-organized and exceeded all my expectations. Will definitely attend again next year.', 'rating' => 5],
            ['content' => 'Great event overall. The speakers were knowledgeable and venue was perfect. Only minor issue was long registration line.', 'rating' => 4],
            ['content' => 'Disappointed with the organization. Event started late and some sessions were cancelled. Expected much better for the price paid.', 'rating' => 2],
            ['content' => 'Fantastic atmosphere and great networking opportunities. Met many interesting people and learned a lot. Highly recommended!', 'rating' => 5],
            ['content' => 'Good content but the venue was too crowded. Hard to move around and find seating. Hope they improve this next time.', 'rating' => 3],
            ['content' => 'One of the best events I\'ve attended this year! The organizers did an excellent job. Everything from registration to sessions was perfect.', 'rating' => 5],
            ['content' => 'Decent event but nothing special. The sessions were informative but I\'ve seen better. Good for networking though.', 'rating' => 3],
            ['content' => 'Outstanding event! The quality of speakers and content was top-notch. Worth every penny. Already looking forward to next year!', 'rating' => 5],
            ['content' => 'Had high expectations but was let down. The venue was nice but the content was lackluster. Expected more value.', 'rating' => 2],
            ['content' => 'Excellent event from start to finish! Well-organized, great speakers, and perfect venue. This is how all events should be run.', 'rating' => 5],
            ['content' => 'Pretty good event overall. Some sessions were better than others but I learned a lot. Would recommend to others.', 'rating' => 4],
            ['content' => 'The event was okay but could use some improvements. The food was great but some sessions felt rushed. Not bad for a first-time event.', 'rating' => 3],
            ['content' => 'Loved every moment of it! The energy was amazing and I met so many wonderful people. This is my new favorite annual event!', 'rating' => 5],
            ['content' => 'Not worth the money. The content was basic and could have been found online for free. Expected much more.', 'rating' => 1],
            ['content' => 'Solid event with good content. The organizers clearly put effort into it. A few hiccups but nothing major.', 'rating' => 4],
            ['content' => 'This was my first time attending and I was blown away! The quality exceeded my expectations. Will definitely be back next year.', 'rating' => 5],
            ['content' => 'Average event. Nothing stood out as particularly good or bad. It was fine but I wouldn\'t go out of my way to attend again.', 'rating' => 3],
            ['content' => 'Exceptional event! The attention to detail was impressive. From the venue to content, everything was top quality.', 'rating' => 5],
            ['content' => 'Had some technical issues during sessions which was frustrating. Other than that, content was good.', 'rating' => 3],
            ['content' => 'Mind-blowing experience! This event changed my perspective on the industry. The speakers were world-class.', 'rating' => 5],
            ['content' => 'The venue location was inconvenient and parking was a nightmare. Once inside though, the content was decent.', 'rating' => 2],
            ['content' => 'Superb event! Every session was valuable and the networking breaks were perfectly timed. A must-attend for industry professionals.', 'rating' => 5],
            ['content' => 'Good event with room for improvement. The speakers were great but the schedule felt a bit disjointed.', 'rating' => 3],
            ['content' => 'Perfectly executed! From the welcoming staff to the closing remarks, everything was professional and polished.', 'rating' => 5],
            ['content' => 'The content was interesting but delivery was dry and hard to stay engaged. More interactive elements would help.', 'rating' => 3],
            ['content' => 'Incredible value for money! The sessions alone were worth the ticket price, not to mention the networking opportunities.', 'rating' => 5],
            ['content' => 'Some technical glitches made it frustrating at times, but the content quality made up for it. Overall positive experience.', 'rating' => 4],
            ['content' => 'Poor audio quality in several rooms made it difficult to hear speakers. Event needs better equipment next time.', 'rating' => 2],
            ['content' => 'Life-changing experience! The connections I made and knowledge I gained will benefit my career for years to come.', 'rating' => 5],
            ['content' => 'Decent content but felt too generic. Wanted more specific, actionable insights rather than broad overview topics.', 'rating' => 3],
            ['content' => 'Outstanding organization! Everything ran smoothly and on schedule. The staff was helpful and the atmosphere was welcoming.', 'rating' => 5],
            ['content' => 'The event was informative but lacked energy. Speakers were knowledgeable but delivery could be more engaging.', 'rating' => 3],
            ['content' => 'Best event I\'ve attended in years! The quality of sessions, networking, and overall experience was unmatched.', 'rating' => 5],
            ['content' => 'Some sessions were excellent while others felt like filler content. More consistency in quality would help.', 'rating' => 3],
            ['content' => 'Impressive lineup of speakers and the content was cutting-edge. Felt like I was getting a sneak peek into the future.', 'rating' => 5],
            ['content' => 'The venue was beautiful but logistics were a mess. Took way too long to get checked in and find my seat.', 'rating' => 2],
            ['content' => 'Great balance of educational content and networking. Left with both new knowledge and valuable contacts!', 'rating' => 5],
            ['content' => 'Content was good but the event felt too long. Would prefer more focused agenda with shorter duration.', 'rating' => 3],
            ['content' => 'World-class event! The speakers were industry leaders and the insights shared were invaluable. Highly recommend.', 'rating' => 5],
            ['content' => 'Had trouble with WiFi throughout the event which was inconvenient. Content was good though.', 'rating' => 3],
            ['content' => 'Exceeded all expectations! The interactive sessions were engaging and I left feeling inspired and motivated.', 'rating' => 5],
            ['content' => 'Average experience. Nothing particularly memorable or impactful. It was fine but won\'t stand out in my memory.', 'rating' => 3],
            ['content' => 'Fantastic job by the organizers! Every detail was thought through and executed flawlessly. Truly enjoyed it.', 'rating' => 5],
            ['content' => 'Some useful content but too much time spent on promotional material. Wanted more practical takeaways.', 'rating' => 3],
            ['content' => 'Amazing event that delivered real value! The workshops were hands-on and I could immediately apply what I learned.', 'rating' => 5],
            ['content' => 'The event was decent but felt overpriced for what was offered. Expected more content and better amenities.', 'rating' => 2],
            ['content' => 'Excellent networking opportunities! Made several meaningful connections that I\'m already following up with.', 'rating' => 5],
            ['content' => 'Good content delivery but the venue was uncomfortable. Room temperature fluctuated and seating was cramped.', 'rating' => 3],
            ['content' => 'Phenomenal event from start to finish! The energy, content, and people all came together perfectly.', 'rating' => 5],
        ];

        $userEventPairs = [];
        $featuredCount = 0;

        $usersArray = $users->toArray();
        $eventsArray = $events->toArray();

        $createdCount = 0;
        $maxAttempts = 500;
        $attempts = 0;

        while ($createdCount < 50 && $attempts < $maxAttempts) {
            $attempts++;

            $user = $usersArray[array_rand($usersArray)];
            $event = $eventsArray[array_rand($eventsArray)];
            $testimonialData = $testimonials[array_rand($testimonials)];

            $pairKey = $user['id'].'-'.$event['id'];

            if (isset($userEventPairs[$pairKey])) {
                continue;
            }

            $userEventPairs[$pairKey] = true;

            $isFeatured = $featuredCount < 10;
            if ($isFeatured) {
                $featuredCount++;
            }

            $isPublished = $featuredCount <= 33;

            $status = $createdCount < 46 ? 'approved' : ($attempts % 2 === 0 ? 'pending' : 'approved');

            Testimonial::create([
                'user_id' => $user['id'],
                'event_id' => $event['id'],
                'content' => $testimonialData['content'],
                'rating' => $testimonialData['rating'],
                'status' => $status,
                'is_published' => $status === 'approved',
                'is_featured' => $isFeatured && $status === 'approved',
            ]);

            $this->command->info('✓ Testimonial '.($createdCount + 1)."/50: {$user['name']} for {$event['title']} (Rating: {$testimonialData['rating']})");
            $createdCount++;
        }

        $this->command->info('Testimonial seeding completed! Created: '.$createdCount);
    }
}
