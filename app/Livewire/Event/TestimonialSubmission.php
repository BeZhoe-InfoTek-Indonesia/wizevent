<?php

namespace App\Livewire\Event;

use App\Jobs\ProcessTestimonialImage;
use App\Models\Event;
use App\Models\Testimonial;
use App\Models\Ticket;
use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.app-visitor')]
class TestimonialSubmission extends Component
{
    use WithFileUploads, WithPagination;

    public Event $event;

    public string $content = '';

    public int $rating = 5;

    public $image;

    public string $imagePreviewUrl = '';

    public bool $showSuccessModal = false;

    public function mount(string $slug): void
    {
        $this->event = Event::where('slug', $slug)
            ->with('testimonials')
            ->firstOrFail();

        $this->checkEligibility();
    }

    public function checkEligibility(): void
    {
        if (! Auth::check()) {
            $this->redirectRoute('login');

            return;
        }

        $user = Auth::user();

        $hasTestimonial = Testimonial::where('user_id', $user->id)
            ->where('event_id', $this->event->id)
            ->exists();

        if ($hasTestimonial) {
            $this->redirectRoute('events.show', ['slug' => $this->event->slug]);

            return;
        }

        $hasCheckedIn = Ticket::whereHas('ticketType', function ($query) {
            $query->where('event_id', $this->event->id);
        })->whereHas('orderItem.order', function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('status', 'completed');
        })->whereNotNull('checked_in_at')->exists();

        if (! $hasCheckedIn) {
            $this->redirectRoute('events.show', ['slug' => $this->event->slug]);

            return;
        }
    }

    public function updatedImage(): void
    {
        if ($this->image) {
            $imageService = app(ImageService::class);
            $errors = $imageService->validateTestimonialImage($this->image);

            if (! empty($errors)) {
                $this->reset('image', 'imagePreviewUrl');
                foreach ($errors as $error) {
                    $this->addError('image', $error);
                }

                return;
            }

            $this->imagePreviewUrl = $this->image->temporaryUrl();
        }
    }

    public function submit(): void
    {
        $this->validate([
            'content' => 'required|min:10|max:1000',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|max:5120|mimes:jpeg,png,webp',
        ]);

        $user = Auth::user();

        $testimonial = Testimonial::create([
            'user_id' => $user->id,
            'event_id' => $this->event->id,
            'content' => $this->content,
            'rating' => $this->rating,
            'status' => 'pending',
            'is_published' => false,
            'is_featured' => false,
        ]);

        if ($this->image) {
            $path = $this->image->store('tmp-testimonials', 'public');
            ProcessTestimonialImage::dispatch($path, $this->image->getClientOriginalName(), $testimonial->id);
        }

        $this->reset('content', 'rating', 'image', 'imagePreviewUrl');
        $this->showSuccessModal = true;
    }

    public function closeSuccessModal(): void
    {
        $this->showSuccessModal = false;
        $this->redirectRoute('events.show', ['slug' => $this->event->slug]);
    }

    public function getStarsProperty(): array
    {
        return [1, 2, 3, 4, 5];
    }

    public function render()
    {
        return view('livewire.event.testimonial-submission');
    }
}
