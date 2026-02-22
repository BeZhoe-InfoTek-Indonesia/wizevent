<?php

namespace App\Livewire\Profile;

use Livewire\Component;

class HelpCenter extends Component
{
    public $searchQuery = '';

    public $selectedCategory = 'all';

    public $expandedFaq = null;

    public $contactForm = [
        'name' => '',
        'email' => '',
        'subject' => '',
        'message' => '',
    ];

    protected $rules = [
        'contactForm.name' => 'required|string|max:255',
        'contactForm.email' => 'required|email|max:255',
        'contactForm.subject' => 'required|string|max:255',
        'contactForm.message' => 'required|string|min:10|max:2000',
    ];

    public function mount()
    {
        $this->contactForm['name'] = auth()->user()->name;
        $this->contactForm['email'] = auth()->user()->email;
    }

    public function setCategory(string $category)
    {
        $this->selectedCategory = $category;
    }

    public function toggleFaq(int $faqId)
    {
        $this->expandedFaq = $this->expandedFaq === $faqId ? null : $faqId;
    }

    public function submitContactForm()
    {
        $this->validate();

        // Here you would typically send an email or store in database
        // For now, we'll just dispatch an event
        $this->dispatch('contact-form-submitted', message: 'Your message has been sent. We\'ll get back to you soon!');

        // Reset form
        $this->contactForm = [
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'subject' => '',
            'message' => '',
        ];
    }

    public function getFilteredFaqsProperty()
    {
        $faqs = $this->faqs;

        if ($this->selectedCategory !== 'all') {
            $faqs = $faqs->where('category', $this->selectedCategory);
        }

        if ($this->searchQuery) {
            $query = strtolower($this->searchQuery);
            $faqs = $faqs->filter(function ($faq) use ($query) {
                return str_contains(strtolower($faq['question']), $query) || 
                       str_contains(strtolower($faq['answer']), $query);
            });
        }

        return $faqs;
    }

    public function getFaqsProperty()
    {
        return collect([
            [
                'id' => 1,
                'category' => 'orders',
                'question' => 'How do I place an order?',
                'answer' => 'To place an order, browse our events, select the tickets you want, and proceed to checkout. You\'ll need to complete the payment process to confirm your order.',
            ],
            [
                'id' => 2,
                'category' => 'orders',
                'question' => 'How do I track my order status?',
                'answer' => 'You can track your order status in the "Your Orders" section of your profile. We\'ll also send you email notifications about any updates to your order.',
            ],
            [
                'id' => 3,
                'category' => 'orders',
                'question' => 'Can I cancel my order?',
                'answer' => 'Yes, you can cancel your order if it\'s still in "Pending Payment" or "Pending Verification" status. Once your payment is verified, cancellations are subject to our refund policy.',
            ],
            [
                'id' => 4,
                'category' => 'payments',
                'question' => 'What payment methods do you accept?',
                'answer' => 'We accept bank transfers and other payment methods. After placing an order, you\'ll receive instructions on how to complete your payment.',
            ],
            [
                'id' => 5,
                'category' => 'payments',
                'question' => 'How long does payment verification take?',
                'answer' => 'Payment verification typically takes 1-2 business days. You\'ll receive an email notification once your payment has been verified.',
            ],
            [
                'id' => 6,
                'category' => 'payments',
                'question' => 'What if my payment is rejected?',
                'answer' => 'If your payment is rejected, you\'ll receive an email with the reason. You can resubmit your payment proof or contact our support team for assistance.',
            ],
            [
                'id' => 7,
                'category' => 'tickets',
                'question' => 'How do I receive my tickets?',
                'answer' => 'Once your payment is verified, you\'ll receive your tickets via email. You can also view and download them from the "Your Orders" section of your profile.',
            ],
            [
                'id' => 8,
                'category' => 'tickets',
                'question' => 'Can I transfer my tickets to someone else?',
                'answer' => 'Tickets are non-transferable. The name on the ticket must match the attendee\'s ID at the event entrance.',
            ],
            [
                'id' => 9,
                'category' => 'tickets',
                'question' => 'What if I lose my ticket?',
                'answer' => 'Don\'t worry! You can always access and reprint your tickets from your profile. Just go to "Your Orders" and click on the order to view your tickets.',
            ],
            [
                'id' => 10,
                'category' => 'account',
                'question' => 'How do I change my password?',
                'answer' => 'Go to the "Account" tab in your profile and scroll to the "Change Password" section. Enter your current password and your new password, then click "Update Password".',
            ],
            [
                'id' => 11,
                'category' => 'account',
                'question' => 'How do I update my profile information?',
                'answer' => 'In the "Account" tab of your profile, you can update your name, email, and profile picture. Click "Save Changes" to update your information.',
            ],
            [
                'id' => 12,
                'category' => 'account',
                'question' => 'How do I delete my account?',
                'answer' => 'To delete your account, please contact our support team. We\'ll guide you through the process and ensure your data is handled according to our privacy policy.',
            ],
            [
                'id' => 13,
                'category' => 'events',
                'question' => 'How do I find events near me?',
                'answer' => 'Use our search and filter features on the events page to find events by location, date, category, or price range.',
            ],
            [
                'id' => 14,
                'category' => 'events',
                'question' => 'Can I get a refund if an event is cancelled?',
                'answer' => 'Yes, if an event is cancelled by the organizer, you\'ll receive a full refund to your original payment method.',
            ],
            [
                'id' => 15,
                'category' => 'events',
                'question' => 'What happens if I can\'t attend an event?',
                'answer' => 'If you can\'t attend an event, please check the event\'s refund policy. Some events may offer refunds or exchanges, while others may not.',
            ],
        ]);
    }

    public function getCategoriesProperty()
    {
        return [
            'all' => 'All Categories',
            'orders' => 'Orders',
            'payments' => 'Payments',
            'tickets' => 'Tickets',
            'account' => 'Account',
            'events' => 'Events',
        ];
    }

    public function render()
    {
        return view('livewire.profile.help-center');
    }
}
