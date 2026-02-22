@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">Privacy Policy</h1>
            
            <div class="prose prose-gray dark:prose-invert max-w-none">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">1. Information We Collect</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    We collect information you provide directly to us, including when you create an account, make a purchase, or communicate with us. This may include your name, email address, phone number, payment information, and other personal details.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">2. How We Use Your Information</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    We use the information we collect to process transactions, send you technical notices and support messages, respond to your comments and questions, provide customer service, and communicate with you about products, services, and events.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">3. Information Sharing</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except as described in this policy. We may share information with event organizers to facilitate ticket delivery and event access.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">4. Data Security</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    We implement a variety of security measures to maintain the safety of your personal information. All sensitive information is transmitted via Secure Socket Layer (SSL) technology and encrypted using industry-standard encryption protocols.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">5. Cookies</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    We use cookies to enhance your experience, analyze site traffic, and for security purposes. You may choose to set your browser to refuse cookies, but some parts of the site may not function properly.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">6. Your Rights</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    You have the right to access, correct, or delete your personal information. You can also object to processing of your personal data, ask us to restrict processing, or request to transfer your data. You can exercise these rights by contacting us or using the settings in your account.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">7. Data Retention</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    We retain your personal information for as long as necessary to provide our services and fulfill the transactions you have requested, or for other essential purposes such as complying with our legal obligations, resolving disputes, and enforcing our agreements.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">8. Children's Privacy</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    Our service is not intended for children under the age of 13. We do not knowingly collect personal information from children under 13. If you are a parent or guardian and believe your child has provided us with personal information, please contact us.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">9. Changes to This Policy</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    We may update this privacy policy from time to time. We will notify you of any changes by posting the new privacy policy on this page and updating the "Last updated" date.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3">10. Contact Us</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    If you have any questions about this privacy policy or our data practices, please contact our support team.
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
