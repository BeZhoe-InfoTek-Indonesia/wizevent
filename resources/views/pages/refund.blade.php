@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">Refund Policy</h1>
            
            <div class="prose prose-gray dark:prose-invert max-w-none">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">1. Refund Eligibility</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    Refunds are processed on a case-by-case basis. Eligibility for refunds depends on the event organizer's policy and the timing of your request. Please review the specific event's refund policy before purchasing.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">2. Event Cancellation by Organizer</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    If an event is cancelled by the organizer, you will receive a full refund to your original payment method. Refunds are typically processed within 5-10 business days.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">3. Event Rescheduling</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    If an event is rescheduled, your ticket will remain valid for the new date. If you cannot attend the rescheduled date, you may request a refund subject to the event organizer's policy.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">4. Customer-Requested Refunds</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    Refunds requested by customers are subject to the following conditions:
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 mb-4 ml-4">
                    <li class="mb-2">Requests must be made at least 48 hours before the event start time</li>
                    <li class="mb-2">A processing fee may apply (typically 10-15% of the ticket price)</li>
                    <li class="mb-2">Some events may have non-refundable tickets</li>
                    <li class="mb-2">Refund approval is at the discretion of the event organizer</li>
                </ul>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">5. How to Request a Refund</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    To request a refund, please contact our support team with your order number and reason for the refund request. You can find your order number in your account under "Your Orders".
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">6. Refund Processing Time</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    Once approved, refunds are typically processed within 5-10 business days. The exact timing depends on your payment method and bank processing times.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">7. Non-Refundable Items</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    The following are generally non-refundable:
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 mb-4 ml-4">
                    <li class="mb-2">Service fees and processing charges</li>
                    <li class="mb-2">Tickets for events that have already occurred</li>
                    <li class="mb-2">Tickets marked as "non-refundable" at the time of purchase</li>
                    <li class="mb-2">Requests made less than 48 hours before the event</li>
                </ul>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">8. Exceptional Circumstances</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    In exceptional circumstances (such as medical emergencies or force majeure events), we may consider refund requests outside of our standard policy. Please contact our support team with documentation of your situation.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">9. Ticket Transfers</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    Tickets are non-transferable unless explicitly stated otherwise. The name on the ticket must match the attendee's ID at the event entrance.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">10. Contact Us</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    If you have any questions about our refund policy or need to request a refund, please contact our support team. We're here to help!
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
