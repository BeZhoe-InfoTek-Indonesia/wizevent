<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\SettingComponent;
use Illuminate\Database\Seeder;

/**
 * Setting Component Seeder
 *
 * Creates master data for event categories, tags, ticket types, and terms & conditions.
 * These are used as dropdown options in the Event creation form and other settings.
 */
class SettingComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding Setting Components...');

        // Event Categories
        $this->seedEventCategories();

        // Event Tags
        $this->seedEventTags();

        // Ticket Types
        $this->seedTicketTypes();

        // Performer Types
        $this->seedPerformerTypes();

        // Performer Professions
        $this->seedPerformerProfessions();

        // Terms & Conditions
        $this->seedTermsAndConditions();

        // Payment Instructions
        $this->seedPaymentInstructions();

        $this->command->info('✓ Setting Components seeded successfully!');
    }

    /**
     * Seed event categories.
     */
    private function seedEventCategories(): void
    {
        $setting = Setting::firstOrCreate(
            ['key' => 'event_categories'],
            [
                'name' => 'Event Categories',
            ]
        );

        $categories = [
            'Music & Concerts',
            'Technology & Conferences',
            'Food & Beverage',
            'Sports & Fitness',
            'Arts & Culture',
            'Business & Networking',
            'Education & Workshops',
            'Family & Kids',
            'Health & Wellness',
            'Entertainment & Shows',
        ];

        foreach ($categories as $category) {
            SettingComponent::firstOrCreate(
                [
                    'setting_id' => $setting->id,
                    'name' => $category,
                ],
                [
                    'type' => 'string',
                    'value' => $category,
                ]
            );
        }

        $this->command->info('  ✓ Event Categories seeded');
    }

    /**
     * Seed event tags.
     */
    private function seedEventTags(): void
    {
        $setting = Setting::firstOrCreate(
            ['key' => 'event_tags'],
            [
                'name' => 'Event Tags',
            ]
        );

        $tags = [
            'Indoor',
            'Outdoor',
            'Free Entry',
            'Paid Event',
            'Family Friendly',
            '18+ Only',
            'VIP Available',
            'Early Bird',
            'Workshop',
            'Networking',
            'Live Performance',
            'Exhibition',
            'Food & Drinks',
            'Parking Available',
            'Accessible',
            'Pet Friendly',
            'Alcohol Served',
            'Cashless',
            'Multi-day',
            'Virtual',
        ];

        foreach ($tags as $tag) {
            SettingComponent::firstOrCreate(
                [
                    'setting_id' => $setting->id,
                    'name' => $tag,
                ],
                [
                    'type' => 'string',
                    'value' => $tag,
                ]
            );
        }

        $this->command->info('  ✓ Event Tags seeded');
    }

    /**
     * Seed ticket types.
     */
    private function seedTicketTypes(): void
    {
        $setting = Setting::firstOrCreate(
            ['key' => 'ticket_types'],
            [
                'name' => 'Ticket Types',
            ]
        );

        $ticketTypes = [
            'Regular',
            'VIP',
            'Early Bird',
            'Student',
            'Senior',
            'Group',
            'Corporate',
            'Premium',
            'Standard',
            'Economy',
            'Backstage',
            'All Access',
            'Day Pass',
            'Weekend Pass',
            'Season Pass',
        ];

        foreach ($ticketTypes as $ticketType) {
            SettingComponent::firstOrCreate(
                [
                    'setting_id' => $setting->id,
                    'name' => $ticketType,
                ],
                [
                    'type' => 'string',
                    'value' => $ticketType,
                ]
            );
        }

        $this->command->info('  ✓ Ticket Types seeded');
    }

    /**
     * Seed performer types.
     */
    private function seedPerformerTypes(): void
    {
        $setting = Setting::firstOrCreate(
            ['key' => 'performer_types'],
            [
                'name' => 'Performer Types',
            ]
        );

        $types = [
            'Music',
            'Dance',
            'Comedy',
            'Theater',
            'Magic',
            'Visual Arts',
            'Film',
            'Literature',
            'Fashion',
            'Photography',
            'Circus',
            'Puppetry',
            'Storytelling',
            'Digital Arts',
        ];

        foreach ($types as $type) {
            SettingComponent::firstOrCreate(
                [
                    'setting_id' => $setting->id,
                    'name' => $type,
                ],
                [
                    'type' => 'string',
                    'value' => $type,
                ]
            );
        }

        $this->command->info('  ✓ Performer Types seeded');
    }

    /**
     * Seed performer professions.
     */
    private function seedPerformerProfessions(): void
    {
        $setting = Setting::firstOrCreate(
            ['key' => 'performer_professions'],
            [
                'name' => 'Performer Professions',
            ]
        );

        $professions = [
            'Singer',
            'DJ',
            'Musician',
            'Dancer',
            'Comedian',
            'Actor',
            'Magician',
            'Speaker',
            'Host/Emcee',
            'Artist',
            'Photographer',
            'Fashion Designer',
            'Model',
            'Circus Performer',
            'Puppeteer',
            'Storyteller',
            'Choreographer',
            'Composer',
            'Producer',
            'Director',
        ];

        foreach ($professions as $profession) {
            SettingComponent::firstOrCreate(
                [
                    'setting_id' => $setting->id,
                    'name' => $profession,
                ],
                [
                    'type' => 'string',
                    'value' => $profession,
                ]
            );
        }

        $this->command->info('  ✓ Performer Professions seeded');
    }

    /**
     * Seed terms & conditions.
     */
    private function seedTermsAndConditions(): void
    {
        $setting = Setting::firstOrCreate(
            ['key' => 'terms_&_condition'],
            [
                'name' => 'Terms & Condition',
            ]
        );

        $termsAndConditions = [
            [
                'name' => 'General Terms',
                'value' => '<h2>General Terms and Conditions</h2>
<p>Welcome to our event management platform. By using our services, you agree to comply with these terms and conditions. Please read them carefully before proceeding with any ticket purchases or event registrations.</p>
<p><strong>1. Acceptance of Terms</strong></p>
<p>By accessing or using our platform, you acknowledge that you have read, understood, and agree to be bound by these terms. If you do not agree to these terms, please do not use our services.</p>
<p><strong>2. User Responsibilities</strong></p>
<p>Users must provide accurate and complete information when registering for events. Any false or misleading information may result in cancellation of tickets and potential legal action.</p>
<p><strong>3. Platform Usage</strong></p>
<p>Our platform is provided on an "as is" basis. We reserve the right to modify, suspend, or discontinue any aspect of the platform at any time without prior notice.</p>
<p><strong>4. Intellectual Property</strong></p>
<p>All content on this platform, including text, graphics, logos, and software, is owned by us or our licensors and is protected by copyright laws.</p>',
            ],
            [
                'name' => 'Refund Policy',
                'value' => '<h2>Refund Policy</h2>
<p>We understand that plans change. This policy outlines the conditions under which refunds may be processed.</p>
<p><strong>1. Refund Eligibility</strong></p>
<p>Refunds are available under the following circumstances:</p>
<ul>
<li>Event cancellation by the organizer</li>
<li>Event rescheduling with at least 7 days notice</li>
<li>Medical emergencies with valid documentation</li>
</ul>
<p><strong>2. Refund Process</strong></p>
<p>Refund requests must be submitted within 7 days of the event cancellation or 14 days before the event date for eligible cases. Processing time is typically 5-10 business days.</p>
<p><strong>3. Non-Refundable Items</strong></p>
<p>The following are not eligible for refunds:</p>
<ul>
<li>Change of mind after purchase</li>
<li>Missed events due to personal reasons</li>
<li>Processing fees and service charges</li>
</ul>
<p><strong>4. Refund Method</strong></p>
<p>Refunds will be processed to the original payment method used for purchase. Please ensure your payment details are up to date.</p>',
            ],
            [
                'name' => 'Privacy Policy',
                'value' => '<h2>Privacy Policy</h2>
<p>Your privacy is important to us. This policy explains how we collect, use, and protect your personal information.</p>
<p><strong>1. Information Collection</strong></p>
<p>We collect information you provide directly, including:</p>
<ul>
<li>Name and contact information</li>
<li>Payment details (processed securely)</li>
<li>Event preferences and history</li>
</ul>
<p><strong>2. Information Usage</strong></p>
<p>We use your information to:</p>
<ul>
<li>Process ticket purchases and registrations</li>
<li>Send event confirmations and updates</li>
<li>Improve our services and user experience</li>
<li>Communicate promotional offers (with consent)</li>
</ul>
<p><strong>3. Data Protection</strong></p>
<p>We implement industry-standard security measures to protect your data. Your information is encrypted and stored securely.</p>
<p><strong>4. Third-Party Sharing</strong></p>
<p>We do not sell your personal information. We may share data with event organizers only as necessary for event delivery and with your consent.</p>
<p><strong>5. Your Rights</strong></p>
<p>You have the right to access, correct, or delete your personal information. Contact us for any privacy-related requests.</p>',
            ],
            [
                'name' => 'Ticket Terms',
                'value' => '<h2>Ticket Terms</h2>
<p>These terms govern the purchase, use, and transfer of event tickets on our platform.</p>
<p><strong>1. Ticket Validity</strong></p>
<p>Tickets are valid only for the specific event, date, time, and seat (if applicable) as indicated on the ticket. Tickets are non-transferable unless explicitly authorized.</p>
<p><strong>2. Ticket Delivery</strong></p>
<p>Digital tickets will be delivered via email and accessible through your account. Ensure your contact information is accurate. Physical tickets, if any, will be shipped to the provided address.</p>
<p><strong>3. Lost or Damaged Tickets</strong></p>
<p>For digital tickets, access your account to retrieve your ticket. For physical tickets, contact customer service immediately. Replacement fees may apply.</p>
<p><strong>4. Age Restrictions</strong></p>
<p>Some events have age restrictions. It is your responsibility to verify and comply with these requirements. Valid ID may be required for entry.</p>
<p><strong>5. Ticket Resale</strong></p>
<p>Unauthorized resale of tickets is prohibited. Use our official resale platform if available. Tickets sold through unauthorized channels may be voided.</p>
<p><strong>6. Multiple Tickets</strong></p>
<p>Purchase limits may apply to certain events. Violating purchase limits may result in order cancellation.</p>',
            ],
            [
                'name' => 'Event Rules - General',
                'value' => '<h2>General Event Rules</h2>
<p>These rules apply to all events hosted on our platform. Additional rules may apply to specific event types.</p>
<p><strong>1. Admission</strong></p>
<p>All attendees must present valid tickets and government-issued ID for entry. Late arrivals may not be admitted depending on the event type.</p>
<p><strong>2. Prohibited Items</strong></p>
<p>The following items are generally prohibited:</p>
<ul>
<li>Weapons or dangerous objects</li>
<li>Illegal substances</li>
<li>Professional cameras or recording equipment</li>
<li>Outside food and beverages</li>
<li>Large bags or backpacks</li>
</ul>
<p><strong>3. Conduct</strong></p>
<p>All attendees must behave respectfully. Disruptive behavior, harassment, or violation of venue rules will result in immediate removal without refund.</p>
<p><strong>4. Smoking Policy</strong></p>
<p>Smoking is prohibited in indoor venues and designated areas. Violators may be asked to leave.</p>
<p><strong>5. Recording and Photography</strong></p>
<p>Unauthorized recording or photography may be prohibited. Check specific event rules before attending.</p>
<p><strong>6. Lost and Found</strong></p>
<p>Report lost items to venue staff. Found items will be held for 30 days. Contact customer service for inquiries.</p>',
            ],
            [
                'name' => 'Event Rules - Music',
                'value' => '<h2>Music Event Rules</h2>
<p>Additional rules specific to music events, concerts, and festivals.</p>
<p><strong>1. Sound Levels</strong></p>
<p>Music events may involve high sound levels. Ear protection is recommended. The venue is not responsible for hearing damage.</p>
<p><strong>2. Crowd Behavior</strong></p>
<p>Mosh pits and crowd surfing are generally prohibited unless explicitly allowed. Respect personal space and safety of others.</p>
<p><strong>3. Stage Access</strong></p>
<p>Unauthorized stage access is strictly prohibited. Violators will be removed and may face legal action.</p>
<p><strong>4. Alcohol Policy</strong></p>
<p>Alcohol may be served at music events. Valid ID is required. Over-intoxication may result in denial of service or removal.</p>
<p><strong>5. Merchandise</strong></p>
<p>Official merchandise may be available for purchase. Counterfeit merchandise is prohibited.</p>
<p><strong>6. Set Times</strong></p>
<p>Artists\' set times are subject to change. Check the event app or website for updates.</p>
<p><strong>7. Re-entry</strong></p>
<p>Re-entry policies vary by venue. Check your ticket or contact customer service for details.</p>',
            ],
            [
                'name' => 'Event Rules - Concert',
                'value' => '<h2>Concert Event Rules</h2>
<p>Specific rules for concert events to ensure a safe and enjoyable experience.</p>
<p><strong>1. Seating Arrangements</strong></p>
<p>For seated concerts, you must sit in your assigned seat. Standing in aisles or blocking views is prohibited.</p>
<p><strong>2. VIP Areas</strong></p>
<p>VIP access requires appropriate ticket. Unauthorized entry to VIP areas will result in removal.</p>
<p><strong>3. Meet and Greets</strong></p>
<p>Meet and greet sessions are available with specific tickets. Follow instructions from venue staff. Time limits apply.</p>
<p><strong>4. Autographs and Photos</strong></p>
<p>Autographs and photos with artists are at their discretion and subject to event-specific rules.</p>
<p><strong>5. Emergency Procedures</strong></p>
<p>Familiarize yourself with emergency exits. Follow staff instructions during emergencies. Do not use elevators during evacuations.</p>
<p><strong>6. Accessibility</strong></p>
<p>Accessible seating is available. Contact us in advance to arrange accommodations. Service animals are welcome.</p>
<p><strong>7. Weather Policy</strong></p>
<p>For outdoor concerts, events may be delayed or cancelled due to severe weather. Refund policies apply for cancellations.</p>',
            ],
        ];

        foreach ($termsAndConditions as $term) {
            SettingComponent::firstOrCreate(
                [
                    'setting_id' => $setting->id,
                    'name' => $term['name'],
                ],
                [
                    'type' => 'html',
                    'value' => $term['value'],
                ]
            );
        }

        $this->command->info('  ✓ Terms & Conditions seeded');
    }

    /**
     * Seed payment instructions.
     */
    private function seedPaymentInstructions(): void
    {
        $setting = Setting::firstOrCreate(
            ['key' => 'payment_instruction'],
            [
                'name' => 'Payment Instruction',
            ]
        );

        $paymentInstructions = [
            [
                'name' => 'Bank Transfer Instructions',
                'value' => '<h2>Bank Transfer Instructions</h2>
<p>Please follow these steps to complete your payment via bank transfer:</p>
<p><strong>1. Select Your Bank</strong></p>
<p>Choose from our supported banks listed below. Each bank has different processing times.</p>
<p><strong>2. Transfer the Exact Amount</strong></p>
<p>Transfer the total amount shown in your order confirmation. Do not include any additional notes or references unless specified.</p>
<p><strong>3. Upload Payment Proof</strong></p>
<p>After completing the transfer, upload a clear screenshot or photo of your payment receipt. Ensure the following details are visible:</p>
<ul>
<li>Bank name and logo</li>
<li>Account number</li>
<li>Transfer amount</li>
<li>Date and time of transfer</li>
<li>Transaction reference number (if available)</li>
</ul>
<p><strong>4. Verification Process</strong></p>
<p>Our finance team will verify your payment within 24-48 business hours. You will receive a notification once your payment is approved.</p>
<p><strong>5. Ticket Delivery</strong></p>
<p>Upon successful verification, your tickets will be sent to your registered email address and available in your account.</p>',
            ],
            [
                'name' => 'E-Wallet Instructions',
                'value' => '<h2>E-Wallet Payment Instructions</h2>
<p>Pay conveniently using your preferred e-wallet service:</p>
<p><strong>Supported E-Wallets:</strong></p>
<ul>
<li>GoPay</li>
<li>OVO</li>
<li>DANA</li>
<li>ShopeePay</li>
<li>LinkAja</li>
</ul>
<p><strong>Payment Steps:</strong></p>
<p>1. Select your e-wallet from the payment options</p>
<p>2. You will be redirected to the e-wallet app or website</p>
<p>3. Confirm the payment amount and authorize the transaction</p>
<p>4. Return to our platform after successful payment</p>
<p>5. Your tickets will be automatically issued</p>
<p><strong>Important Notes:</strong></p>
<ul>
<li>Ensure sufficient balance in your e-wallet before payment</li>
<li>Payment confirmation is instant</li>
<li>No additional fees for e-wallet payments</li>
<li>Keep the transaction receipt for your records</li>
</ul>',
            ],
            [
                'name' => 'Credit/Debit Card Instructions',
                'value' => '<h2>Credit/Debit Card Payment Instructions</h2>
<p>Securely pay using your credit or debit card:</p>
<p><strong>Accepted Cards:</strong></p>
<ul>
<li>Visa</li>
<li>Mastercard</li>
<li>JCB</li>
<li>American Express</li>
</ul>
<p><strong>Payment Process:</strong></p>
<p>1. Enter your card details (card number, expiry date, CVV)</p>
<p>2. Provide billing information if required</p>
<p>3. Complete 3D Secure verification if prompted</p>
<p>4. Wait for payment confirmation</p>
<p>5. Receive your tickets instantly</p>
<p><strong>Security Information:</strong></p>
<ul>
<li>All transactions are encrypted with SSL technology</li>
<li>We do not store your card details on our servers</li>
<li>3D Secure adds an extra layer of protection</li>
<li>Your payment information is PCI DSS compliant</li>
</ul>
<p><strong>Failed Payments:</strong></p>
<p>If your payment fails, please check:</p>
<ul>
<li>Sufficient credit limit or balance</li>
<li>Correct card details entered</li>
<li>Card is not expired or blocked</li>
<li>Internet connection is stable</li>
</ul>',
            ],
            [
                'name' => 'Payment Verification Guidelines',
                'value' => '<h2>Payment Verification Guidelines</h2>
<p>To ensure smooth processing of your payment, please follow these guidelines:</p>
<p><strong>Payment Proof Requirements:</strong></p>
<ul>
<li>Clear and readable image (JPG, PNG, or PDF)</li>
<li>File size should not exceed 5MB</li>
<li>Must show complete transaction details</li>
<li>Upload within 24 hours of order placement</li>
</ul>
<p><strong>Verification Timeline:</strong></p>
<ul>
<li>Standard verification: 24-48 business hours</li>
<li>Peak periods may take longer</li>
<li>You will receive email notifications at each stage</li>
</ul>
<p><strong>Common Rejection Reasons:</strong></p>
<ul>
<li>Blurry or unreadable payment proof</li>
<li>Incorrect transfer amount</li>
<li>Payment proof from different order</li>
<li>Expired payment proof (older than 7 days)</li>
<li>Missing transaction details</li>
</ul>
<p><strong>What to Do If Payment is Rejected:</strong></p>
<ol>
<li>Check the rejection reason in your order status</li>
<li>Upload a new, clear payment proof</li>
<li>Contact customer support if you believe the rejection was incorrect</li>
<li>Ensure all required details are visible in the new proof</li>
</ol>',
            ],
            [
                'name' => 'Refund and Cancellation Policy',
                'value' => '<h2>Refund and Cancellation Policy</h2>
<p>Understanding our refund and cancellation policies:</p>
<p><strong>Order Cancellation:</strong></p>
<ul>
<li>Cancellation allowed within 24 hours of order placement</li>
<li>No cancellation fee if payment is not yet verified</li>
<li>After verification, cancellation is subject to event organizer approval</li>
</ul>
<p><strong>Refund Eligibility:</strong></p>
<ul>
<li>Event cancelled by organizer: Full refund</li>
<li>Event rescheduled with 7+ days notice: Full refund or transfer to new date</li>
<li>Medical emergency with documentation: Case-by-case review</li>
<li>Technical issues preventing ticket delivery: Full refund</li>
</ul>
<p><strong>Non-Refundable Situations:</strong></p>
<ul>
<li>Change of mind after payment verification</li>
<li>Unable to attend due to personal reasons</li>
<li>Lost or misplaced tickets (can be reissued from account)</li>
<li>Expired payment proof without re-upload</li>
</ul>
<p><strong>Refund Process:</strong></p>
<ol>
<li>Submit refund request through your order page</li>
<li>Provide required documentation</li>
<li>Wait for approval (3-5 business days)</li>
<li>Refund processed to original payment method</li>
<li>Processing time: 5-10 business days depending on bank</li>
</ol>
<p><strong>Partial Refunds:</strong></p>
<p>For partial refunds (e.g., some tickets cancelled), the refund amount will be calculated based on the ticket price at the time of purchase, excluding any non-refundable fees.</p>',
            ],
        ];

        foreach ($paymentInstructions as $instruction) {
            SettingComponent::firstOrCreate(
                [
                    'setting_id' => $setting->id,
                    'name' => $instruction['name'],
                ],
                [
                    'type' => 'html',
                    'value' => $instruction['value'],
                ]
            );
        }

        $this->command->info('  ✓ Payment Instructions seeded');
    }
}
