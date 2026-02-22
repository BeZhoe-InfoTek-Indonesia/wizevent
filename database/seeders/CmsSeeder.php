<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\CmsPage;
use App\Models\EmailTemplate;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\PaymentBank;
use App\Models\PaymentInstruction;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        $this->createFaqCategories();
        $this->createFaqs();
        $this->createBanners();
        $this->createPaymentBanks();
        $this->createPaymentInstructions();
        $this->createEmailTemplates();
        $this->createCmsPages();
    }

    private function createFaqCategories(): void
    {
        FaqCategory::firstOrCreate(
            ['slug' => 'general'],
            [
                'name' => 'General',
                'slug' => 'general',
                'order' => 1,
                'is_active' => true,
            ]
        );

        FaqCategory::firstOrCreate(
            ['slug' => 'payments'],
            [
                'name' => 'Payments',
                'slug' => 'payments',
                'order' => 2,
                'is_active' => true,
            ]
        );

        FaqCategory::firstOrCreate(
            ['slug' => 'tickets'],
            [
                'name' => 'Tickets',
                'slug' => 'tickets',
                'order' => 3,
                'is_active' => true,
            ]
        );
    }

    private function createFaqs(): void
    {
        $generalCategory = FaqCategory::where('slug', 'general')->first();
        $paymentCategory = FaqCategory::where('slug', 'payments')->first();
        $ticketCategory = FaqCategory::where('slug', 'tickets')->first();

        if ($generalCategory) {
            Faq::firstOrCreate(
                ['question' => 'What is the event ticket management system?'],
                [
                    'category_id' => $generalCategory->id,
                    'question' => 'What is the event ticket management system?',
                    'answer' => 'Our event ticket management system is a platform that allows you to discover, purchase, and manage tickets for various events in one convenient place.',
                    'order' => 1,
                    'is_active' => true,
                ]
            );
        }

        if ($paymentCategory) {
            Faq::firstOrCreate(
                ['question' => 'What payment methods do you accept?'],
                [
                    'category_id' => $paymentCategory->id,
                    'question' => 'What payment methods do you accept?',
                    'answer' => 'We accept bank transfers, e-wallets, and QRIS. You can select your preferred payment method during checkout.',
                    'order' => 1,
                    'is_active' => true,
                ]
            );

            Faq::firstOrCreate(
                ['question' => 'How long do I have to complete my payment?'],
                [
                    'category_id' => $paymentCategory->id,
                    'question' => 'How long do I have to complete my payment?',
                    'answer' => 'You have 24 hours to complete your payment after placing your order. If payment is not received within this period, your order will be cancelled.',
                    'order' => 2,
                    'is_active' => true,
                ]
            );
        }

        if ($ticketCategory) {
            Faq::firstOrCreate(
                ['question' => 'How do I access my digital tickets?'],
                [
                    'category_id' => $ticketCategory->id,
                    'question' => 'How do I access my digital tickets?',
                    'answer' => 'After your payment is verified, your digital tickets will be sent to your email. You can also access them from your account dashboard.',
                    'order' => 1,
                    'is_active' => true,
                ]
            );
        }
    }

    private function createBanners(): void
    {
        Banner::firstOrCreate(
            ['title' => 'Welcome to Event Management'],
            [
                'title' => 'Welcome to Event Management',
                'type' => 'hero',
                'image_path' => 'https://via.placeholder.com/1200x400',
                'link_url' => '/events',
                'link_target' => '_self',
                'position' => 1,
                'is_active' => true,
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
                'click_count' => 0,
                'impression_count' => 0,
            ]
        );
    }

    private function createPaymentBanks(): void
    {
        PaymentBank::firstOrCreate(
            ['account_number' => '1234567890'],
            [
                'bank_name' => 'BCA',
                'account_number' => '1234567890',
                'account_holder' => 'Event Management',
                'qr_code_path' => null,
                'logo_path' => 'https://via.placeholder.com/100x100',
                'is_active' => true,
                'order' => 1,
            ]
        );

        PaymentBank::firstOrCreate(
            ['account_number' => '0987654321'],
            [
                'bank_name' => 'Mandiri',
                'account_number' => '0987654321',
                'account_holder' => 'Event Management',
                'qr_code_path' => null,
                'logo_path' => 'https://via.placeholder.com/100x100',
                'is_active' => true,
                'order' => 2,
            ]
        );
    }

    private function createPaymentInstructions(): void
    {
        PaymentInstruction::firstOrCreate(
            ['payment_method' => 'transfer', 'locale' => 'en'],
            [
                'payment_method' => 'transfer',
                'content' => 'Please transfer the total amount to one of our bank accounts listed above. Make sure to include your order number in the transfer note for verification purposes.',
                'is_active' => true,
                'locale' => 'en',
            ]
        );

        PaymentInstruction::firstOrCreate(
            ['payment_method' => 'qris', 'locale' => 'en'],
            [
                'payment_method' => 'qris',
                'content' => 'Scan the QR code above using your mobile banking app or e-wallet app. Complete the payment and keep the receipt for verification.',
                'is_active' => true,
                'locale' => 'en',
            ]
        );
    }

    private function createEmailTemplates(): void
    {
        EmailTemplate::firstOrCreate(
            ['key' => 'order_created'],
            [
                'key' => 'order_created',
                'name' => 'Order Created Notification',
                'subject' => 'Your Order #{{ $order_number }} has been Created',
                'html_content' => '<h1>Thank you for your order!</h1><p>Your order #{{ $order_number }} has been created successfully.</p><p>Total Amount: {{ $total_amount }}</p><p>Please complete your payment within 24 hours.</p>',
                'text_content' => null,
                'variables' => json_encode(['order_number', 'total_amount']),
                'locale' => 'en',
                'is_default' => true,
            ]
        );

        EmailTemplate::firstOrCreate(
            ['key' => 'payment_approved'],
            [
                'key' => 'payment_approved',
                'name' => 'Payment Approved Notification',
                'subject' => 'Payment Verified for Order #{{ $order_number }}',
                'html_content' => '<h1>Payment Verified!</h1><p>Your payment for order #{{ $order_number }} has been verified successfully.</p><p>Your digital tickets are now available in your account.</p>',
                'text_content' => null,
                'variables' => json_encode(['order_number']),
                'locale' => 'en',
                'is_default' => true,
            ]
        );
    }

    private function createCmsPages(): void
    {
        CmsPage::firstOrCreate(
            ['slug' => 'about'],
            [
                'title' => 'About Us',
                'slug' => 'about',
                'content' => json_encode([
                    ['type' => 'text', 'content' => 'Welcome to our Event Ticket Management System. We provide a comprehensive platform for event discovery and ticket purchasing.'],
                ]),
                'status' => 'published',
                'seo_title' => 'About Us - Event Ticket Management',
                'seo_description' => 'Learn more about our event ticket management platform and how we can help you discover and attend great events.',
                'og_image' => null,
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ]
        );

        CmsPage::firstOrCreate(
            ['slug' => 'privacy-policy'],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => json_encode([
                    ['type' => 'text', 'content' => 'This privacy policy explains how we collect, use, and protect your personal information.'],
                ]),
                'status' => 'published',
                'seo_title' => 'Privacy Policy',
                'seo_description' => 'Read our privacy policy to understand how we handle your personal information.',
                'og_image' => null,
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ]
        );

        CmsPage::firstOrCreate(
            ['slug' => 'terms-of-service'],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'content' => json_encode([
                    ['type' => 'text', 'content' => 'By using our platform, you agree to these terms of service.'],
                ]),
                'status' => 'published',
                'seo_title' => 'Terms of Service',
                'seo_description' => 'Read our terms of service to understand your rights and responsibilities when using our platform.',
                'og_image' => null,
                'published_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ]
        );
    }
}
