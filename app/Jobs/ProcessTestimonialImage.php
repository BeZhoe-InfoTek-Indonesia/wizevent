<?php

namespace App\Jobs;

use App\Models\Testimonial;
use App\Services\ImageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable as QueueableTrait;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessTestimonialImage implements ShouldQueue
{
    use InteractsWithQueue, Queueable, QueueableTrait, SerializesModels;

    public function __construct(
        public string $imagePath,
        public string $originalName,
        public int $testimonialId
    ) {}

    public function handle(): void
    {
        $testimonial = Testimonial::find($this->testimonialId);

        if (! $testimonial) {
            return;
        }

        $imageService = app(ImageService::class);

        $result = $imageService->processTestimonialImage($this->imagePath, $this->originalName, $this->testimonialId);

        $testimonial->update($result);

        // Clean up temporary file
        \Illuminate\Support\Facades\Storage::disk('public')->delete($this->imagePath);
    }
}
