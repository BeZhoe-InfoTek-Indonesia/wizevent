@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">Terms & Conditions</h1>
            
            <div class="prose prose-gray dark:prose-invert max-w-none">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">1. Acceptance of Terms</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    By accessing and using this event ticketing platform, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by these terms, please do not use this service.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">2. User Accounts</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    You are responsible for maintaining the confidentiality of your account and password and for restricting access to your computer. You agree to accept responsibility for all activities that occur under your account or password.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">3. Ticket Purchases</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    All ticket purchases are subject to availability and confirmation of the order price. We reserve the right at any time after receipt of your order to accept or decline your order for any reason.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">4. Payment Terms</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    Payment must be made in full before the event date. We accept various payment methods as indicated on our website. Your order will not be confirmed until payment is received and verified.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">5. Refund Policy</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    Refunds are handled on a case-by-case basis. Please refer to our Refund Policy for detailed information about refund eligibility and procedures.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">6. Event Cancellations</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    In the event that an event is cancelled by the organizer, you will receive a full refund to your original payment method. We are not responsible for any additional expenses incurred.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">7. Privacy Policy</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    Your use of our service is also subject to our Privacy Policy. Please review our Privacy Policy, which also governs the service and informs users of our data collection practices.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">8. Limitation of Liability</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    In no event shall we be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">9. Changes to Terms</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    We reserve the right to modify these terms at any time. All changes are effective immediately when we post them. Your continued use of the service following the posting of changes constitutes your acceptance of such changes.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">10. Contact Information</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    If you have any questions about these Terms & Conditions, please contact our support team.
                </p>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Last updated: {{ now()->format('F j, Y') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
