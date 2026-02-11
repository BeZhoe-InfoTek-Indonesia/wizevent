<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_number' => 'TKT-' . strtoupper(fake()->bothify('###????')),
            'holder_name' => fake()->name(),
            'status' => 'active',
            'qr_code_content' => encrypt('test-content'),
        ];
    }
}
