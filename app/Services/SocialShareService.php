<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Str;

class SocialShareService
{
    public function generateShareUrl(Event $event, string $platform): string
    {
        $eventUrl = route('events.show', $event->slug);
        $utmParams = $this->getUtmParameters($platform);

        return $eventUrl.'?'.http_build_query($utmParams);
    }

    public function getShareUrlForPlatform(Event $event, string $platform): string
    {
        $shareUrl = $this->generateShareUrl($event, $platform);
        $eventTitle = urlencode($event->title);
        $eventDescription = urlencode(Str::limit($event->description, 200));

        return match ($platform) {
            'facebook' => sprintf(
                'https://www.facebook.com/sharer/sharer.php?u=%s&quote=%s',
                urlencode($shareUrl),
                $eventTitle
            ),
            'twitter' => sprintf(
                'https://twitter.com/intent/tweet?url=%s&text=%s',
                urlencode($shareUrl),
                $eventTitle
            ),
            'linkedin' => sprintf(
                'https://www.linkedin.com/sharing/share-offsite/?url=%s',
                urlencode($shareUrl)
            ),
            'whatsapp' => sprintf(
                'https://wa.me/?text=%s %s',
                $eventTitle,
                urlencode($shareUrl)
            ),
            'telegram' => sprintf(
                'https://t.me/share/url?url=%s&text=%s',
                urlencode($shareUrl),
                $eventTitle
            ),
            'email' => sprintf(
                'mailto:?subject=%s&body=%s',
                $eventTitle,
                urlencode($shareUrl)
            ),
            default => $shareUrl,
        };
    }

    protected function getUtmParameters(string $platform): array
    {
        return [
            'utm_source' => $platform,
            'utm_medium' => 'social',
            'utm_campaign' => 'event_share',
        ];
    }

    public function getSupportedPlatforms(): array
    {
        return [
            'facebook' => [
                'name' => 'Facebook',
                'icon' => 'facebook',
                'color' => '#1877F2',
            ],
            'twitter' => [
                'name' => 'Twitter/X',
                'icon' => 'x-twitter',
                'color' => '#000000',
            ],
            'linkedin' => [
                'name' => 'LinkedIn',
                'icon' => 'linkedin',
                'color' => '#0A66C2',
            ],
            'whatsapp' => [
                'name' => 'WhatsApp',
                'icon' => 'whatsapp',
                'color' => '#25D366',
            ],
            'telegram' => [
                'name' => 'Telegram',
                'icon' => 'telegram',
                'color' => '#0088CC',
            ],
            'email' => [
                'name' => 'Email',
                'icon' => 'envelope',
                'color' => '#666666',
            ],
        ];
    }
}
