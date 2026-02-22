<?php

namespace App\Services;

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class HtmlSanitizerService
{
    /**
     * Sanitize HTML input using Symfony HtmlSanitizer with a safe config.
     */
    public function sanitize(string $html): string
    {
        $config = (new HtmlSanitizerConfig())
            ->allowSafeElements()
            ->allowRelativeLinks(false)
            ->allowRelativeMedias(false);

        $sanitizer = new HtmlSanitizer($config);

        return $sanitizer->sanitize($html);
    }
}
