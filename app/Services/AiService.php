<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Gemini\Laravel\Facades\Gemini;
use Exception;

class AiService
{
    /**
     * Generate an enhanced event description.
     *
     * @param array $data
     * @return string|null
     */
    public function generateDescription(array $data): ?string
    {
        $organizers = (array) ($data['organizers'] ?? []);
        $performers = (array) ($data['performers'] ?? []);

        // Ensure string inputs are strings (handle translatable fields or accidental arrays)
        $title = $this->convertToString($data['title'] ?? '');
        $date = $this->convertToString($data['event_date'] ?? '');
        $location = $this->convertToString($data['location'] ?? '');
        $venueName = $this->convertToString($data['venue_name'] ?? '');
        $shortDescription = $this->convertToString($data['short_description'] ?? '');
        $targetAudience = $this->convertToString($data['target_audience'] ?? '');
        $keyActivities = $this->convertToString($data['key_activities'] ?? '');
        $tone = (string) ($data['ai_tone_style'] ?? 'professional');
        $categories = (array) ($data['categories'] ?? []);
        $tags = (array) ($data['tags'] ?? []);
        $organizers = (array) ($data['organizers'] ?? []);
        $performers = (array) ($data['performers'] ?? []);

        $prompt = $this->buildDescriptionPrompt($title, $date, $location, $venueName, $shortDescription, $targetAudience, $keyActivities, $tone, $categories, $tags, $organizers, $performers);

        try {
            if (config('gemini.api_key')) {
                $result = $this->generateWithGemini($prompt);
                if ($result) {
                    return $result;
                }
            }

            // Fallback to OpenAI if configured
            $openaiKey = config('services.openai.key');
            if ($openaiKey) {
                $model = config('services.openai.model', 'gpt-4o');
                
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer {$openaiKey}",
                ])->timeout(30)->post("https://api.openai.com/v1/chat/completions", [
                    'model' => $model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an expert event marketer and copywriter who only speaks in well-formatted HTML results.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 2048,
                ]);

                if ($response->successful()) {
                    $result = $response->json();
                    return $result['choices'][0]['message']['content'] ?? null;
                }

                Log::error('OpenAI API Error: ' . $response->body());
            }

            // Final fallback to mocked description
            return $this->getMockedDescription($title, $tone);

        } catch (\Exception $e) {
            Log::error('AI Description Generation Failed: ' . $e->getMessage());
            return $this->getMockedDescription($title, $tone);
        }
    }

    /**
     * Generate a polished event concept from rough notes.
     *
     * @param array $data
     * @return string|null
     */
    public function generateConcept(array $data): ?string
    {
        $title = $this->convertToString($data['title'] ?? '');
        $category = $this->convertToString($data['event_category'] ?? '');
        $audience = $this->convertToString($data['target_audience_description'] ?? '');
        $notes = $this->convertToString($data['notes'] ?? '');
        $budget = $this->convertToString($data['budget_target'] ?? '');

        $prompt = $this->buildConceptPrompt($title, $category, $audience, $notes, $budget);

        try {
            if (config('gemini.api_key')) {
                $result = $this->generateWithGemini($prompt);
                if ($result) {
                    return $result;
                }
            }

            $openaiKey = config('services.openai.key');
            if ($openaiKey) {
                $model = config('services.openai.model', 'gpt-4o');
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer {$openaiKey}",
                ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an expert event strategist and copywriter. Respond in well-formatted HTML.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 2048,
                ]);

                if ($response->successful()) {
                    $result = $response->json();
                    return $result['choices'][0]['message']['content'] ?? null;
                }

                Log::error('OpenAI API Error (generateConcept): ' . $response->body());
            }

            return $this->getMockedConcept($title, $category);
        } catch (\Exception $e) {
            Log::error('AI Concept Generation Failed: ' . $e->getMessage());
            return $this->getMockedConcept($title, $category);
        }
    }

    /**
     * Generate a budget forecast for an event plan.
     *
     * @param array $data
     * @return array|null
     */
    public function generateBudgetForecast(array $data): ?array
    {
        $category = $this->convertToString($data['event_category'] ?? '');
        $audienceSize = $data['target_audience_size'] ?? 0;
        $location = $this->convertToString($data['location'] ?? '');
        $eventDate = $this->convertToString($data['event_date'] ?? '');
        $budgetTarget = $data['budget_target'] ?? 0;

        $prompt = $this->buildBudgetForecastPrompt($category, (int) $audienceSize, $location, $eventDate, (float) $budgetTarget);

        try {
            if (config('gemini.api_key')) {
                $result = $this->generateJsonWithGemini($prompt);
                if ($result) {
                    return $result;
                }
            }

            $openaiKey = config('services.openai.key');
            if ($openaiKey) {
                $model = config('services.openai.model', 'gpt-4o');
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer {$openaiKey}",
                ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an expert event budget analyst. Respond ONLY with valid JSON, no markdown.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.4,
                    'max_tokens' => 1024,
                ]);

                if ($response->successful()) {
                    $raw = $response->json()['choices'][0]['message']['content'] ?? null;
                    if ($raw) {
                        $decoded = json_decode($raw, true);
                        if (is_array($decoded)) {
                            return $decoded;
                        }
                    }
                }

                Log::error('OpenAI API Error (generateBudgetForecast): ' . $response->body());
            }

            return $this->getMockedBudgetForecast($category, (int) $audienceSize);
        } catch (\Exception $e) {
            Log::error('AI Budget Forecast Failed: ' . $e->getMessage());
            return $this->getMockedBudgetForecast($category, (int) $audienceSize);
        }
    }

    /**
     * Suggest a ticket pricing strategy for an event plan.
     *
     * @param array $data
     * @return array|null
     */
    public function suggestPricingStrategy(array $data): ?array
    {
        $category = $this->convertToString($data['event_category'] ?? '');
        $audienceSize = (int) ($data['target_audience_size'] ?? 0);
        $revenueTarget = (float) ($data['revenue_target'] ?? 0);
        $budgetTarget = (float) ($data['budget_target'] ?? 0);
        $location = $this->convertToString($data['location'] ?? '');

        $prompt = $this->buildPricingStrategyPrompt($category, $audienceSize, $revenueTarget, $budgetTarget, $location);

        try {
            if (config('gemini.api_key')) {
                $result = $this->generateJsonWithGemini($prompt);
                if ($result) {
                    return $result;
                }
            }

            $openaiKey = config('services.openai.key');
            if ($openaiKey) {
                $model = config('services.openai.model', 'gpt-4o');
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer {$openaiKey}",
                ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an expert event pricing strategist. Respond ONLY with valid JSON, no markdown.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.5,
                    'max_tokens' => 1024,
                ]);

                if ($response->successful()) {
                    $raw = $response->json()['choices'][0]['message']['content'] ?? null;
                    if ($raw) {
                        $decoded = json_decode($raw, true);
                        if (is_array($decoded)) {
                            return $decoded;
                        }
                    }
                }

                Log::error('OpenAI API Error (suggestPricingStrategy): ' . $response->body());
            }

            return $this->getMockedPricingStrategy($audienceSize, $revenueTarget);
        } catch (\Exception $e) {
            Log::error('AI Pricing Strategy Failed: ' . $e->getMessage());
            return $this->getMockedPricingStrategy($audienceSize, $revenueTarget);
        }
    }

    /**
     * Assess risks for an event plan.
     *
     * @param array $data
     * @return array|null
     */
    public function assessRisks(array $data): ?array
    {
        $category = $this->convertToString($data['event_category'] ?? '');
        $audienceSize = (int) ($data['target_audience_size'] ?? 0);
        $location = $this->convertToString($data['location'] ?? '');
        $eventDate = $this->convertToString($data['event_date'] ?? '');
        $budgetTarget = (float) ($data['budget_target'] ?? 0);
        $revenueTarget = (float) ($data['revenue_target'] ?? 0);

        $prompt = $this->buildRiskAssessmentPrompt($category, $audienceSize, $location, $eventDate, $budgetTarget, $revenueTarget);

        try {
            if (config('gemini.api_key')) {
                $result = $this->generateJsonWithGemini($prompt);
                if ($result) {
                    return $result;
                }
            }

            $openaiKey = config('services.openai.key');
            if ($openaiKey) {
                $model = config('services.openai.model', 'gpt-4o');
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer {$openaiKey}",
                ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an expert event risk analyst. Respond ONLY with valid JSON, no markdown.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.4,
                    'max_tokens' => 1024,
                ]);

                if ($response->successful()) {
                    $raw = $response->json()['choices'][0]['message']['content'] ?? null;
                    if ($raw) {
                        $decoded = json_decode($raw, true);
                        if (is_array($decoded)) {
                            return $decoded;
                        }
                    }
                }

                Log::error('OpenAI API Error (assessRisks): ' . $response->body());
            }

            return $this->getMockedRiskAssessment($category, $eventDate);
        } catch (\Exception $e) {
            Log::error('AI Risk Assessment Failed: ' . $e->getMessage());
            return $this->getMockedRiskAssessment($category, $eventDate);
        }
    }

    /**
     * Generate SEO metadata for an event.
     *
     * @param array $data
     * @return array|null
     */
    public function generateSeoMetadata(array $data): ?array
    {
        $title = $this->convertToString($data['title'] ?? '');
        $description = $this->convertToString($data['description'] ?? '');
        $shortDescription = $this->convertToString($data['short_description'] ?? '');
        $categories = (array) ($data['categories'] ?? []);
        $tags = (array) ($data['tags'] ?? []);
        $venueName = $this->convertToString($data['venue_name'] ?? '');
        $organizers = (array) ($data['organizers'] ?? []);
        $performers = (array) ($data['performers'] ?? []);

        $prompt = $this->buildSeoPrompt($title, $description, $shortDescription, $categories, $tags, $venueName, $organizers, $performers);

        try {
            if (config('gemini.api_key')) {
                $result = $this->generateJsonWithGemini($prompt);
                if ($result) {
                    // Safety truncation to ensure it matches form limits
                    if (isset($result['title'])) {
                        $result['title'] = substr($result['title'], 0, 60);
                    }
                    if (isset($result['description'])) {
                        $result['description'] = substr($result['description'], 0, 160);
                    }
                    return $result;
                }
            }

            $openaiKey = config('services.openai.key');
            if ($openaiKey) {
                $model = config('services.openai.model', 'gpt-4o');
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer {$openaiKey}",
                ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an SEO expert. Respond ONLY with valid JSON, no markdown.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.5,
                    'max_tokens' => 1024,
                ]);

                if ($response->successful()) {
                    $raw = $response->json()['choices'][0]['message']['content'] ?? null;
                    if ($raw) {
                        $decoded = json_decode($raw, true);
                        if (is_array($decoded)) {
                            // Safety truncation for OpenAI too
                            if (isset($decoded['title'])) {
                                $decoded['title'] = substr($decoded['title'], 0, 60);
                            }
                            if (isset($decoded['description'])) {
                                $decoded['description'] = substr($decoded['description'], 0, 160);
                            }
                            return $decoded;
                        }
                    }
                }

                Log::error('OpenAI API Error (generateSeoMetadata): ' . $response->body());
            }

            return $this->getMockedSeo($title);
        } catch (\Exception $e) {
            Log::error('AI SEO Generation Failed: ' . $e->getMessage());
            return $this->getMockedSeo($title);
        }
    }

    /**
     * Helper to generate content using Gemini as primary AI provider.
     */
    protected function generateWithGemini(string $prompt): ?string
    {
        try {
            $modelName = config('services.gemini.model', 'gemini-1.5-flash');
            
            $response = Gemini::generativeModel($modelName)
                ->generateContent('You are an expert event marketer and copywriter who only speaks in well-formatted HTML results. ' . $prompt);

            $text = $response->text();
            
            if ($text) {
                info('Gemini Response success');
                return $text;
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Gemini API Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Build the prompt for description generation.
     */
    protected function buildDescriptionPrompt(
        string $title,
        string $date,
        string $location,
        string $venueName,
        string $shortDescription,
        string $targetAudience,
        string $keyActivities,
        string $tone,
        array $categories = [],
        array $tags = [],
        array $organizers = [],
        array $performers = []
    ): string {
        $categoriesStr  = !empty($categories) ? implode(', ', array_map(fn($v) => $this->convertToString($v), $categories)) : '';
        $tagsStr        = !empty($tags) ? implode(', ', array_map(fn($v) => $this->convertToString($v), $tags)) : '';
        $organizersStr  = !empty($organizers) ? implode(', ', array_map(fn($v) => $this->convertToString($v), $organizers)) : '';
        $performersStr  = !empty($performers) ? implode(', ', array_map(fn($v) => $this->convertToString($v), $performers)) : '';

        $lines = [
            'Create a detailed, engaging, and event description based on the following information:',
            '',
            "Event Title: {$title}",
            "Date: {$date}",
            "Location: {$location}",
        ];

        if ($venueName !== '') {
            $lines[] = "Venue Name: {$venueName}";
        }
        if ($shortDescription !== '') {
            $lines[] = "Short Description / Tagline: {$shortDescription}";
        }
        if ($targetAudience !== '') {
            $lines[] = "Target Audience: {$targetAudience}";
        }
        if ($keyActivities !== '') {
            $lines[] = "Key Activities: {$keyActivities}";
        }
        if ($categoriesStr !== '') {
            $lines[] = "Event Categories: {$categoriesStr}";
        }
        if ($tagsStr !== '') {
            $lines[] = "Event Tags: {$tagsStr}";
        }
        if ($organizersStr !== '') {
            $lines[] = "Organized by: {$organizersStr}";
        }
        if ($performersStr !== '') {
            $lines[] = "Performers / Speakers: {$performersStr}";
        }

        $lines = array_merge($lines, [
            "Desired Tone/Style: {$tone}",
            '',
            'Instructions:',
            '1. Create a compelling, high-converting headline.',
            '2. Write an engaging introduction that immediately captures interest.',
            '3. Detail the key activities and explain why they are unique or must-see.',
            '4. Integrate the venue/location and date naturally into the narrative.',
            '5. Expertly highlight the performers/speakers, showcasing their significance and value to the audience.',
            '6. Mention the organizers briefly to build credibility and trust.',
            '7. Use the event categories and tags to naturally weave in relevant keywords for SEO.',
            '8. Ensure the entire copy maintains a consistent ' . $tone . ' tone.',
            '9. Use bullet points for key features, activities, or benefits for better readability.',
            '10. Include a clear, persuasive call to action (CTA) at the end.',
            '11. Format the output in clean HTML (only use <h2>, <h3>, <p>, <ul>, <li>, <strong>, <em>).',
            '',
            'Output only the HTML description. No preamble or closing remarks.',
        ]);

        return implode("\n", $lines);
    }

    /**
     * Provide a mocked description if API is not available (for demonstration).
     */
    protected function getMockedDescription($title, $tone): string
    {
        $toneDesc = [
            'professional' => 'a sleek and formal experience',
            'casual' => 'a fun and relaxed gathering',
            'promotional' => 'the most exciting event of the year!',
        ][$tone] ?? 'an amazing experience';

        return <<<HTML
<h2>Join us for {$title}!</h2>
<p>Get ready for <strong>{$title}</strong>, {$toneDesc} that you won't want to miss. We've crafted this event specifically to provide value and entertainment for our guests.</p>

<h3>What to Expect</h3>
<ul>
    <li>Insightful sessions and activities tailored to your interests.</li>
    <li>Networking opportunities with participants and experts.</li>
    <li>A vibrant atmosphere at our carefully selected location.</li>
</ul>

<h3>Key Highlights</h3>
<p>This event features a range of activities designed to engage and inspire. Whether you're here to learn, connect, or simply enjoy, {$title} has something for everyone.</p>

<p><em>Mark your calendars and join us for an unforgettable day!</em></p>

<h3>Register Now</h3>
<p>Secure your spot today and be part of this incredible journey. We look forward to seeing you there!</p>
HTML;
    }

    /**
     * Helper to generate JSON content using Gemini.
     *
     * @param string $prompt
     * @param string $apiKey
     * @return array|null
     */
    protected function generateJsonWithGemini(string $prompt): ?array
    {
        try {
            $modelName = config('services.gemini.model', 'gemini-1.5-flash');
            
            $response = Gemini::generativeModel($modelName)
                ->generateContent('You are a JSON-only responder. Do not include markdown or code blocks. ' . $prompt);

            $raw = $response->text();
            
            if ($raw) {
                // Strip markdown code fences if present
                $raw = preg_replace('/^```(?:json)?\s*/m', '', $raw);
                $raw = preg_replace('/```\s*$/m', '', $raw);
                $decoded = json_decode(trim($raw), true);
                if (is_array($decoded)) {
                    return $decoded;
                }
            }

            Log::error('Gemini JSON API Error: No text returned or invalid JSON');
            return null;
        } catch (\Exception $e) {
            Log::error('Gemini JSON API Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Build the prompt for event concept generation.
     */
    protected function buildConceptPrompt(string $title, string $category, string $audience, string $notes, mixed $budget): string
    {
        return <<<PROMPT
Create a compelling, professional event concept for the following:

Event Title: {$title}
Event Category: {$category}
Target Audience: {$audience}
Budget Estimate: {$budget}
Organizer Notes: {$notes}

Instructions:
1. Write a catchy headline.
2. Write an engaging introduction paragraph.
3. List 3-5 key highlights or selling points.
4. Write a compelling call-to-action.
5. Use HTML formatting only (<h2>, <h3>, <p>, <ul>, <li>, <strong>, <em>).

Output only the HTML concept. No preamble or explanation.
PROMPT;
    }

    /**
     * Build the prompt for budget forecast generation.
     */
    protected function buildBudgetForecastPrompt(string $category, int $audienceSize, string $location, string $eventDate, float $budgetTarget): string
    {
        return <<<PROMPT
You are an event budget analyst. Generate a detailed budget forecast as JSON for this event:

Category: {$category}
Audience Size: {$audienceSize}
Location: {$location}
Event Date: {$eventDate}
Budget Target: {$budgetTarget}

Return a JSON object with this exact structure:
{
  "categories": [
    {
      "category": "Venue Rental",
      "estimated_amount": 5000,
      "percentage_of_total": 25,
      "notes": "Includes main hall + breakout rooms"
    }
  ],
  "total_estimated": 20000,
  "contingency_amount": 2000,
  "contingency_percentage": 10,
  "summary": "Brief summary of the forecast"
}

Include these categories: Venue Rental, Talent/Performers, Security & Safety, Marketing & Promotion, Logistics & Equipment, Staffing, Insurance, Contingency.
Base amounts on the audience size and event type. Return valid JSON only.
PROMPT;
    }

    /**
     * Build the prompt for pricing strategy generation.
     */
    protected function buildPricingStrategyPrompt(string $category, int $audienceSize, float $revenueTarget, float $budgetTarget, string $location): string
    {
        return <<<PROMPT
You are an event pricing strategist. Suggest three ticket pricing scenarios (pessimistic, realistic, optimistic) for this event as JSON:

Category: {$category}
Audience Size: {$audienceSize}
Revenue Target: {$revenueTarget}
Budget Target: {$budgetTarget}
Location: {$location}

Return a JSON object with this exact structure:
{
  "scenarios": {
    "pessimistic": {
      "label": "Pessimistic",
      "tiers": [
        {
          "name": "General Admission",
          "price": 50,
          "quantity": 80,
          "sales_start_days_before": 60,
          "sales_end_days_before": 0,
          "projected_revenue": 4000,
          "description": "Conservative pricing for lower demand"
        }
      ],
      "total_projected_revenue": 12000,
      "revenue_target": {$revenueTarget},
      "target_met": false,
      "surplus_deficit": -8000,
      "recommendation": "Conservative estimate"
    },
    "realistic": {
      "label": "Realistic",
      "tiers": [...],
      "total_projected_revenue": 20000,
      "revenue_target": {$revenueTarget},
      "target_met": true,
      "surplus_deficit": 0,
      "recommendation": "Expected scenario"
    },
    "optimistic": {
      "label": "Optimistic",
      "tiers": [...],
      "total_projected_revenue": 30000,
      "revenue_target": {$revenueTarget},
      "target_met": true,
      "surplus_deficit": 10000,
      "recommendation": "Best-case scenario"
    }
  },
  "selected_scenario": "realistic"
}

Each scenario should have 3-5 tiers (e.g., Early Bird, Presale, General Admission, VIP). Return valid JSON only.
PROMPT;
    }

    /**
     * Build the prompt for risk assessment.
     */
    protected function buildRiskAssessmentPrompt(string $category, int $audienceSize, string $location, string $eventDate, float $budgetTarget, float $revenueTarget): string
    {
        return <<<PROMPT
You are an event risk analyst. Assess risks for this event as JSON:

Category: {$category}
Audience Size: {$audienceSize}
Location: {$location}
Event Date: {$eventDate}
Budget Target: {$budgetTarget}
Revenue Target: {$revenueTarget}

Return a JSON object with this exact structure:
{
  "risks": [
    {
      "dimension": "Weather & Environmental",
      "severity": "Medium",
      "description": "Risk description",
      "mitigation": "Mitigation suggestion"
    }
  ],
  "overall_score": 45,
  "overall_rating": "Medium",
  "summary": "Brief overall assessment"
}

Assess these dimensions: Weather & Environmental, Audience Target Mismatch, Budget Adequacy, Timeline Feasibility, Regulatory & Compliance.
Severity values: Low, Medium, High, Critical. Overall score 0-100 (higher = riskier). Return valid JSON only.
PROMPT;
    }

    /**
     * Mocked concept for demo mode.
     */
    protected function getMockedConcept(string $title, string $category): string
    {
        return <<<HTML
<!-- DEMO MODE: Configure AI keys for real results -->
<h2>✨ {$title}</h2>
<p>This is a <strong>demo concept</strong> for your <em>{$category}</em> event. Configure your Gemini or OpenAI API key to generate a real AI-powered concept.</p>

<h3>Key Highlights</h3>
<ul>
    <li>An immersive experience tailored for your target audience.</li>
    <li>World-class programming and curated activities.</li>
    <li>Networking and community-building opportunities.</li>
    <li>Memorable moments that attendees will talk about.</li>
</ul>

<h3>Why Attend?</h3>
<p>This event is designed to inspire, educate, and connect. Whether you're a first-time attendee or a returning fan, there's something for everyone at <strong>{$title}</strong>.</p>

<h3>Secure Your Spot</h3>
<p>Tickets are limited. Register today and be part of this extraordinary experience!</p>
HTML;
    }

    /**
     * Mocked budget forecast for demo mode.
     *
     * @return array<string, mixed>
     */
    protected function getMockedBudgetForecast(string $category, int $audienceSize): array
    {
        $base = max(1000, $audienceSize * 20);
        return [
            'categories' => [
                ['category' => 'Venue Rental', 'estimated_amount' => round($base * 0.30), 'percentage_of_total' => 30, 'notes' => 'Demo estimate'],
                ['category' => 'Talent/Performers', 'estimated_amount' => round($base * 0.25), 'percentage_of_total' => 25, 'notes' => 'Demo estimate'],
                ['category' => 'Security & Safety', 'estimated_amount' => round($base * 0.10), 'percentage_of_total' => 10, 'notes' => 'Demo estimate'],
                ['category' => 'Marketing & Promotion', 'estimated_amount' => round($base * 0.12), 'percentage_of_total' => 12, 'notes' => 'Demo estimate'],
                ['category' => 'Logistics & Equipment', 'estimated_amount' => round($base * 0.10), 'percentage_of_total' => 10, 'notes' => 'Demo estimate'],
                ['category' => 'Staffing', 'estimated_amount' => round($base * 0.08), 'percentage_of_total' => 8, 'notes' => 'Demo estimate'],
                ['category' => 'Insurance', 'estimated_amount' => round($base * 0.03), 'percentage_of_total' => 3, 'notes' => 'Demo estimate'],
                ['category' => 'Contingency', 'estimated_amount' => round($base * 0.12), 'percentage_of_total' => 12, 'notes' => 'Demo 12% contingency'],
            ],
            'total_estimated' => $base,
            'contingency_amount' => round($base * 0.12),
            'contingency_percentage' => 12,
            'summary' => '[DEMO MODE] Configure AI keys for real estimates. Based on audience size of ' . $audienceSize . ' for a ' . $category . ' event.',
        ];
    }

    /**
     * Mocked pricing strategy for demo mode (three scenarios).
     *
     * @return array<string, mixed>
     */
    protected function getMockedPricingStrategy(int $audienceSize, float $revenueTarget): array
    {
        $audienceSize = max(100, $audienceSize);
        $basePrice = $revenueTarget > 0 ? round($revenueTarget / $audienceSize, 2) : 50.0;

        $buildScenario = function (string $label, float $priceMult, float $fillRate) use ($audienceSize, $basePrice, $revenueTarget): array {
            $tiers = [
                ['name' => 'Early Bird', 'price' => round($basePrice * $priceMult * 0.7, 0), 'quantity' => (int) round($audienceSize * $fillRate * 0.2), 'sales_start_days_before' => 90, 'sales_end_days_before' => 60, 'description' => 'Limited early access'],
                ['name' => 'Presale', 'price' => round($basePrice * $priceMult * 0.85, 0), 'quantity' => (int) round($audienceSize * $fillRate * 0.25), 'sales_start_days_before' => 60, 'sales_end_days_before' => 30, 'description' => 'Second wave'],
                ['name' => 'General Admission', 'price' => round($basePrice * $priceMult, 0), 'quantity' => (int) round($audienceSize * $fillRate * 0.40), 'sales_start_days_before' => 30, 'sales_end_days_before' => 0, 'description' => 'Standard ticket'],
                ['name' => 'VIP', 'price' => round($basePrice * $priceMult * 2.0, 0), 'quantity' => (int) round($audienceSize * $fillRate * 0.15), 'sales_start_days_before' => 90, 'sales_end_days_before' => 0, 'description' => 'Premium access'],
            ];
            foreach ($tiers as &$tier) {
                $tier['projected_revenue'] = $tier['price'] * $tier['quantity'];
            }
            unset($tier);
            $total = array_sum(array_column($tiers, 'projected_revenue'));
            return [
                'label' => $label,
                'tiers' => $tiers,
                'total_projected_revenue' => $total,
                'revenue_target' => $revenueTarget,
                'target_met' => $total >= $revenueTarget,
                'surplus_deficit' => round($total - $revenueTarget, 2),
                'recommendation' => "[DEMO MODE] {$label} scenario — configure AI keys for real suggestions.",
            ];
        };

        return [
            'scenarios' => [
                'pessimistic' => $buildScenario('Pessimistic', 0.8, 0.6),
                'realistic' => $buildScenario('Realistic', 1.0, 0.8),
                'optimistic' => $buildScenario('Optimistic', 1.2, 1.0),
            ],
            'selected_scenario' => 'realistic',
        ];
    }

    /**
     * Mocked risk assessment for demo mode.
     *
     * @return array<string, mixed>
     */
    protected function getMockedRiskAssessment(string $category, string $eventDate): array
    {
        return [
            'risks' => [
                ['dimension' => 'Weather & Environmental', 'severity' => 'Medium', 'description' => 'Outdoor event risks depend on season and location.', 'mitigation' => 'Secure indoor backup venue or marquee.'],
                ['dimension' => 'Audience Target Mismatch', 'severity' => 'Low', 'description' => 'Category and audience size appear compatible.', 'mitigation' => 'Validate through pre-event surveys.'],
                ['dimension' => 'Budget Adequacy', 'severity' => 'Medium', 'description' => 'Budget data provided; verify contingency allowance.', 'mitigation' => 'Maintain minimum 10-15% contingency reserve.'],
                ['dimension' => 'Timeline Feasibility', 'severity' => 'Low', 'description' => 'Event date appears to allow sufficient planning time.', 'mitigation' => 'Track milestones with a detailed project timeline.'],
                ['dimension' => 'Regulatory & Compliance', 'severity' => 'Low', 'description' => 'Standard permits may be required for ' . $category . '.', 'mitigation' => 'Confirm local event permit requirements early.'],
            ],
            'overall_score' => 35,
            'overall_rating' => 'Low',
            'summary' => '[DEMO MODE] Configure AI keys for real risk assessment. Category: ' . $category . '.',
        ];
    }

    /**
     * Build the prompt for SEO metadata generation.
     */
    protected function buildSeoPrompt(string $title, string $description, string $shortDescription, array $categories, array $tags = [], string $venueName = '', array $organizers = [], array $performers = []): string
    {
        $cats = implode(', ', array_map(fn($v) => $this->convertToString($v), $categories));
        $tagsStr = implode(', ', array_map(fn($v) => $this->convertToString($v), $tags));
        $organizersStr = implode(', ', array_map(fn($v) => $this->convertToString($v), $organizers));
        $performersStr = implode(', ', array_map(fn($v) => $this->convertToString($v), $performers));

        return <<<PROMPT
You are an SEO expert. Generate optimized SEO metadata for this event as JSON:

Title: {$title}
Short Description: {$shortDescription}
Description: {$description}
Venue: {$venueName}
Categories: {$cats}
Tags: {$tagsStr}
Organizers: {$organizersStr}
Performers: {$performersStr}

Return a JSON object with this exact structure:
{
  "title": "Optimized SEO Title",
  "description": "Optimized Meta Description",
  "keywords": "comma, separated, relevant, keywords"
}

Constraints:
1. The "title" MUST be between 50 and 60 characters long.
2. The "description" MUST be between 150 and 160 characters long.
3. The "keywords" should contain 10-20 relevant keywords.

Ensure the title and description are compelling and include relevant keywords. Return valid JSON only.
PROMPT;
    }

    /**
     * Generate a short description from a full description.
     *
     * @param array $data
     * @return string|null
     */
    public function generateShortDescription(array $data): ?string
    {
        $description = $this->convertToString($data['description'] ?? '');
        $title = $this->convertToString($data['title'] ?? '');

        if (empty($description)) {
            return null;
        }

        $prompt = $this->buildShortDescriptionPrompt($title, $description);

        try {
            if (config('gemini.api_key')) {
                $result = $this->generateWithGemini($prompt);
                if ($result) {
                    // Strip HTML tags and limit to 500 characters
                    $text = strip_tags($result);
                    return substr($text, 0, 500);
                }
            }

            // Fallback to OpenAI if configured
            $openaiKey = config('services.openai.key');
            if ($openaiKey) {
                $model = config('services.openai.model', 'gpt-4o');

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer {$openaiKey}",
                ])->timeout(30)->post("https://api.openai.com/v1/chat/completions", [
                    'model' => $model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an expert copywriter who creates compelling short descriptions. Respond with plain text only, no HTML or markdown.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 500,
                ]);

                if ($response->successful()) {
                    $result = $response->json();
                    $text = $result['choices'][0]['message']['content'] ?? null;
                    if ($text) {
                        return substr(trim($text), 0, 500);
                    }
                }

                Log::error('OpenAI API Error (generateShortDescription): ' . $response->body());
            }

            // Final fallback to mocked short description
            return $this->getMockedShortDescription($title, $description);

        } catch (\Exception $e) {
            Log::error('AI Short Description Generation Failed: ' . $e->getMessage());
            return $this->getMockedShortDescription($title, $description);
        }
    }

    /**
     * Build the prompt for short description generation.
     */
    protected function buildShortDescriptionPrompt(string $title, string $description): string
    {
        // Strip HTML from description for the prompt
        $plainDescription = strip_tags($description);

        return <<<PROMPT
Create a compelling, concise short description (maximum 500 characters) for this event:

Event Title: {$title}

Full Description:
{$plainDescription}

Instructions:
1. Create a punchy, engaging short description that captures the essence of the event.
2. Focus on the main value proposition and key highlights.
3. Use an exciting, inviting tone.
4. Keep it under 500 characters.
5. Respond with plain text only, no HTML or markdown.

Output only the short description text. No preamble or explanation.
PROMPT;
    }

    /**
     * Mocked short description for demo mode.
     */
    protected function getMockedShortDescription(string $title, string $description): string
    {
        $plainDescription = strip_tags($description);
        $firstSentence = preg_replace('/[.!?].*$/', '', $plainDescription);
        $shortDesc = trim($firstSentence);

        if (empty($shortDesc)) {
            $shortDesc = "Join us for {$title}! An amazing event featuring great activities, networking opportunities, and unforgettable experiences. Don't miss out!";
        }

        return substr($shortDesc, 0, 500);
    }

    /**
     * Provide mocked SEO for demo mode.
     */
    protected function getMockedSeo(string $title): array
    {
        return [
            'title' => str_pad(substr($title . ' | Event Management Highlights', 0, 60), 50, ' '),
            'description' => str_pad(substr('Join us for ' . $title . '. A premier event featuring world-class speakers, networking opportunities, and unforgettable experiences. Secure your spot now!', 0, 160), 150, ' '),
            'keywords' => 'event, ' . strtolower($title) . ', registration, tickets, activities',
        ];
    }

    /**
     * Generate an event rundown/agenda from plan parameters.
     *
     * @param array $data
     * @return array|null
     */
    public function generateRundown(array $data): ?array
    {
        $category = $this->convertToString($data['event_category'] ?? '');
        $audienceSize = (int) ($data['target_audience_size'] ?? 0);
        $location = $this->convertToString($data['location'] ?? '');
        $talents = (array) ($data['talents'] ?? []);

        $prompt = $this->buildRundownPrompt($category, $audienceSize, $location, $talents);

        try {
            if (config('gemini.api_key')) {
                $result = $this->generateJsonWithGemini($prompt);
                if ($result) {
                    return $result;
                }
            }

            $openaiKey = config('services.openai.key');
            if ($openaiKey) {
                $model = config('services.openai.model', 'gpt-4o');
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer {$openaiKey}",
                ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an expert event producer. Respond ONLY with valid JSON, no markdown.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.6,
                    'max_tokens' => 1024,
                ]);

                if ($response->successful()) {
                    $raw = $response->json()['choices'][0]['message']['content'] ?? null;
                    if ($raw) {
                        $decoded = json_decode($raw, true);
                        if (is_array($decoded)) {
                            return $decoded;
                        }
                    }
                }

                Log::error('OpenAI API Error (generateRundown): ' . $response->body());
            }

            return $this->getMockedRundown($category, $talents);
        } catch (\Exception $e) {
            Log::error('AI Rundown Generation Failed: ' . $e->getMessage());
            return $this->getMockedRundown($category, $talents);
        }
    }

    /**
     * Build the prompt for rundown generation.
     *
     * @param array<int, array<string, mixed>> $talents
     */
    protected function buildRundownPrompt(string $category, int $audienceSize, string $location, array $talents): string
    {
        $talentList = empty($talents)
            ? 'No confirmed talents yet.'
            : implode("\n", array_map(fn ($t) => "- {$t['name']}" . (isset($t['duration']) ? " ({$t['duration']} min)" : ''), $talents));

        return <<<PROMPT
You are an expert event producer. Generate a time-blocked event agenda (rundown) as JSON for this event:

Category: {$category}
Audience Size: {$audienceSize}
Location: {$location}
Confirmed Performers:
{$talentList}

Return a JSON object with this exact structure:
{
  "items": [
    {
      "title": "Registration & Check-in",
      "description": "Guests arrive and collect entry wristbands",
      "start_time": "18:00",
      "end_time": "19:00",
      "type": "registration",
      "performer_name": null
    }
  ]
}

Include these segment types as appropriate: registration, setup, ceremony, performance (for each talent), networking, break, other.
For performance slots, use the talent name in "performer_name" field and respect their duration if given.
Use 24-hour time format. Start event at 18:00 unless category implies otherwise. Return valid JSON only.
PROMPT;
    }

    /**
     * Mocked rundown for demo mode.
     *
     * @param array<int, array<string, mixed>> $talents
     * @return array<string, mixed>
     */
    protected function getMockedRundown(string $category, array $talents): array
    {
        $items = [
            ['title' => 'Registration & Check-in', 'description' => 'Guests arrive and receive their entry wristbands.', 'start_time' => '17:00', 'end_time' => '18:00', 'type' => 'registration', 'performer_name' => null],
            ['title' => 'Stage & Technical Setup', 'description' => 'Final sound check and stage preparation.', 'start_time' => '18:00', 'end_time' => '18:30', 'type' => 'setup', 'performer_name' => null],
            ['title' => 'Opening Ceremony', 'description' => 'Welcome address and opening remarks.', 'start_time' => '18:30', 'end_time' => '19:00', 'type' => 'ceremony', 'performer_name' => null],
        ];

        $hour = 19;
        $minute = 0;

        if (! empty($talents)) {
            foreach ($talents as $i => $talent) {
                $duration = (int) ($talent['duration'] ?? 60);
                $startTime = sprintf('%02d:%02d', $hour, $minute);
                $minute += $duration;
                $hour += intdiv($minute, 60);
                $minute = $minute % 60;
                $endTime = sprintf('%02d:%02d', $hour, $minute);

                $items[] = [
                    'title' => 'Performance: ' . ($talent['name'] ?? "Performer " . ($i + 1)),
                    'description' => 'Live performance set.',
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'type' => 'performance',
                    'performer_name' => $talent['name'] ?? null,
                ];

                // Add intermission if not last talent
                if ($i < count($talents) - 1) {
                    $intStart = $endTime;
                    $minute += 15;
                    $hour += intdiv($minute, 60);
                    $minute = $minute % 60;
                    $items[] = [
                        'title' => 'Intermission',
                        'description' => 'Short break between performances.',
                        'start_time' => $intStart,
                        'end_time' => sprintf('%02d:%02d', $hour, $minute),
                        'type' => 'break',
                        'performer_name' => null,
                    ];
                }
            }
        } else {
            $items[] = ['title' => 'Performance Slot 1', 'description' => 'Main performance.', 'start_time' => '19:00', 'end_time' => '20:00', 'type' => 'performance', 'performer_name' => null];
            $items[] = ['title' => 'Performance Slot 2', 'description' => 'Second performance.', 'start_time' => '20:15', 'end_time' => '21:15', 'type' => 'performance', 'performer_name' => null];
        }

        $items[] = ['title' => 'Networking & Wrap-up', 'description' => 'Guests mingle and event concludes.', 'start_time' => sprintf('%02d:%02d', $hour, $minute), 'end_time' => sprintf('%02d:%02d', $hour + 1, $minute), 'type' => 'networking', 'performer_name' => null];

        return ['items' => $items];
    }

    /**
     * Safely convert mixed input (string, array, nested array) to a single string.
     */
    private function convertToString(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_array($value)) {
            // Flatten the array (including nested arrays) and join with space
            $flattened = [];
            array_walk_recursive($value, function ($item) use (&$flattened) {
                if (is_scalar($item) || (is_object($item) && method_exists($item, '__toString'))) {
                    $flattened[] = (string) $item;
                }
            });

            return implode(' ', $flattened);
        }

        return (string) ($value ?? '');
    }
}
