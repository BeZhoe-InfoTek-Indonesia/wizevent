<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentProof>
 */
class PaymentProofFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => \App\Models\Order::factory(),
            'file_bucket_id' => \App\Models\FileBucket::factory(),
            'status' => 'pending',
            'rejection_reason' => null,
            'verified_at' => null,
            'verified_by' => null,
        ];
    }
}
