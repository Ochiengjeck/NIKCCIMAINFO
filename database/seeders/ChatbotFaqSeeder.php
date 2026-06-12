<?php

namespace Database\Seeders;

use App\Models\ChatbotFaq;
use Illuminate\Database\Seeder;

class ChatbotFaqSeeder extends Seeder
{
    /**
     * Seed a broad, realistic FAQ knowledge base for the public chat widget.
     *
     * The widget (App\Livewire\Public\ChatWidget) matches a visitor's message
     * against the `question` field using substring + similar_text() scoring
     * (threshold 40%). To maximise coverage we phrase questions the way users
     * actually type them and add paraphrase variants for the busiest topics.
     * Idempotent — keyed on `question`, safe to re-run.
     */
    public function run(): void
    {
        $faqs = [
            // ── About / General ────────────────────────────────────────────
            ['what is nikccima', 'about', 'NiKCCIMA is the Nigeria-Kenya Chamber of Commerce, Industry, Mines & Agriculture. We connect businesses across the Nigeria-Kenya trade corridor and help members trade, invest and grow under the AfCFTA (African Continental Free Trade Area).'],
            ['what does nikccima do', 'about', 'We promote bilateral trade and investment between Nigeria and Kenya. Our work spans four pillars: Executive & Governance, Trade & Investment, Policy & Research, and Administration. We run trade missions, B2B matchmaking, advocacy on trade barriers, and member services.'],
            ['what is afcfta', 'about', 'AfCFTA is the African Continental Free Trade Area — a single continental market that lets goods and services move across African countries with reduced tariffs and barriers. NiKCCIMA helps members take advantage of AfCFTA along the Nigeria-Kenya corridor.'],
            ['what are your pillars', 'about', 'NiKCCIMA operates on four pillars: 1) Executive & Governance, 2) Trade & Investment, 3) Policy & Research, and 4) Administration & Member Services. You can read more on the Pillars page of our website.'],
            ['who leads nikccima', 'about', 'NiKCCIMA is led by its Governing Council and Secretariat, with chapter leadership in Nigeria and Kenya. You can see profiles of our leadership on the Leadership page of the website.'],
            ['where are you located', 'about', 'We operate two national chapters — one in Nigeria and one in Kenya — coordinated by a Global Secretariat. For office addresses and directions, please see our Contact page.'],

            // ── Membership ─────────────────────────────────────────────────
            ['how do i become a member', 'membership', 'You can join online: visit the Membership page and click "Apply", choose your membership category, and complete the application form. After review you will receive an invoice (where applicable) and, once approved, a membership certificate.'],
            ['how do i join nikccima', 'membership', 'Becoming a member is easy — go to the Membership page, select a category that fits your organisation, and submit the apply form. Our team reviews each application and follows up by email.'],
            ['how do i apply for membership', 'membership', 'Head to the Membership > Apply page, pick a category, and fill in your details. You will get a confirmation email, an invoice if your tier has a fee, and a certificate after approval.'],
            ['what are the membership categories', 'membership', 'We offer Platinum, Gold, Silver and Bronze tiers, plus special categories: Government / Public Institution, Diplomatic / International Partner, Youth / Startup, and Honorary / Special. See the Membership page for what each includes.'],
            ['what are the membership tiers', 'membership', 'Our ranked tiers are Platinum, Gold, Silver and Bronze. We also have Government/Public, Diplomatic/International Partner, Youth/Startup and Honorary categories for specific applicants.'],
            ['how much does membership cost', 'membership', 'Fees depend on the tier. Indicative annual fees are Platinum ₦2,500,000, Gold ₦1,500,000, Bronze ₦450,000 and Silver ₦300,000. Government, Diplomatic, Youth/Startup and Honorary categories are handled case-by-case. Final pricing is confirmed on your invoice.'],
            ['what is the membership fee', 'membership', 'Membership fees vary by category — for example Platinum ₦2,500,000, Gold ₦1,500,000, Bronze ₦450,000 and Silver ₦300,000 annually. The exact amount appears on the invoice you receive after applying.'],
            ['what are the benefits of membership', 'membership', 'Members get B2B matchmaking, access to trade leads and investment opportunities, invitations to events and trade missions, policy advocacy support, a directory listing, and AfCFTA trade guidance.'],
            ['why should i join', 'membership', 'Membership opens doors to verified trade leads, investor connections, exclusive events, advocacy on the trade barriers you face, and practical AfCFTA support across the Nigeria-Kenya corridor.'],
            ['can individuals join or only companies', 'membership', 'Both can join. Companies typically apply under the corporate tiers, while individuals, youth and startups can apply under the categories that fit them. Choose the relevant option on the apply form.'],
            ['is there a membership for startups', 'membership', 'Yes — we have a Youth / Startup Member category designed for early-stage founders and young entrepreneurs. Select it on the Membership > Apply page.'],
            ['how do i renew my membership', 'membership', 'Membership runs annually. You will be reminded by email before expiry, and you can renew by paying the renewal invoice. If you need help, contact the Secretariat via the Contact page.'],
            ['how long does approval take', 'membership', 'Applications are reviewed by the Secretariat, usually within a few business days. You will receive emails at each step, and a certificate once you are approved.'],
            ['how do i get my membership certificate', 'membership', 'Once your application is approved (and any fee is paid), a membership certificate is issued to you. If you cannot find it, reach out through the Contact page and we will resend it.'],

            // ── Payments ───────────────────────────────────────────────────
            ['how do i pay my membership fee', 'payments', 'After your application is reviewed you receive an invoice with a secure online payment link. Payments are processed through Flutterwave — you can pay by card or other supported methods. Your status updates automatically once payment is confirmed.'],
            ['what payment methods do you accept', 'payments', 'Online payments are handled securely via Flutterwave, which supports cards and other local methods. The payment link is included on your invoice.'],
            ['how do i get an invoice', 'payments', 'An invoice is generated automatically after your membership application is processed (for tiers that have a fee) and emailed to you. You can also request one via the Contact page.'],
            ['is my payment secure', 'payments', 'Yes. Payments are processed by Flutterwave over a secure, encrypted connection — NiKCCIMA does not store your card details.'],
            ['can i get a refund', 'payments', 'Refund requests are handled case-by-case by the Secretariat. Please reach out through the Contact page with your payment details and reason.'],

            // ── Trade & Investment ─────────────────────────────────────────
            ['how do i find trade opportunities', 'trade', 'Members can browse current trade leads on our Trade & Investment page and get matched with partners through our B2B matchmaking service. Joining as a member unlocks the full set of opportunities.'],
            ['what is b2b matchmaking', 'trade', 'B2B matchmaking connects you with relevant buyers, suppliers and partners across the Nigeria-Kenya corridor based on your sector and needs. It is one of the core benefits of membership.'],
            ['how do i post a trade lead', 'trade', 'Verified members can submit trade leads (offers to buy or sell). Get in touch through the Contact page or your member dashboard and our Trade team will help you publish it.'],
            ['how can i export to kenya', 'trade', 'We help Nigerian businesses reach Kenyan markets through matchmaking, market information and AfCFTA guidance on tariffs and rules of origin. Start by exploring the Trade page or contacting our Trade desk.'],
            ['how can i export to nigeria', 'trade', 'Kenyan businesses can use our corridor services — matchmaking, market intelligence and AfCFTA guidance — to enter the Nigerian market. Visit the Trade page or contact us to begin.'],
            ['how do i connect with investors', 'trade', 'NiKCCIMA links members with vetted investors and investment opportunities along the corridor. Explore the Trade & Investment section or contact the Secretariat for an introduction.'],
            ['what are trade corridors', 'trade', 'A trade corridor is an established route and set of facilitation measures that make moving goods between markets easier. We focus on strengthening the Nigeria-Kenya corridor under AfCFTA.'],

            // ── Policy & Research ──────────────────────────────────────────
            ['how do i report a trade barrier', 'policy', 'You can report a Non-Tariff Barrier (NTB) — such as customs delays, unfair charges or documentation hurdles — to our Policy team. Use the Contact page or the Policy section so we can log it and advocate on your behalf.'],
            ['what is a non tariff barrier', 'policy', 'A Non-Tariff Barrier (NTB) is an obstacle to trade other than a tariff — for example licensing rules, quotas, customs delays or technical requirements. NiKCCIMA tracks and advocates against NTBs affecting members.'],
            ['where can i find policy briefs', 'policy', 'Published policy briefs and research are available in the Policy section of the website. They cover trade rules, AfCFTA implementation and corridor issues.'],
            ['what are rules of origin', 'policy', 'Rules of Origin determine whether a product qualifies as "made in" an AfCFTA country and so qualifies for preferential tariffs. Our Policy team can guide members on meeting them.'],
            ['can you help with tariffs', 'policy', 'Yes — our Policy & Research pillar provides guidance on tariffs, rules of origin and AfCFTA preferences. Reach out via the Policy section or Contact page.'],

            // ── Events ─────────────────────────────────────────────────────
            ['what events do you have', 'events', 'We host trade missions, B2B forums, networking sessions and webinars. See the Events page for the upcoming schedule, and members receive priority invitations.'],
            ['how do i register for an event', 'events', 'Open the Events page, choose an event, and follow the registration link. Members often get reserved seats and member rates.'],
            ['do you organise trade missions', 'events', 'Yes. NiKCCIMA runs trade missions between Nigeria and Kenya so members can meet partners, visit markets and sign deals. Watch the Events page for the next mission.'],
            ['how can i sponsor an event', 'events', 'Sponsorship packages are available for our events and missions. Contact the Secretariat through the Contact page and our team will share the options.'],

            // ── Chapters ───────────────────────────────────────────────────
            ['do you have a nigeria chapter', 'chapters', 'Yes — the Nigeria Chapter serves members and businesses in Nigeria. See the Nigeria Chapter page for its leadership, events and focus areas.'],
            ['do you have a kenya chapter', 'chapters', 'Yes — the Kenya Chapter serves members and businesses in Kenya. Visit the Kenya Chapter page for details on its leadership and activities.'],
            ['which chapter should i join', 'chapters', 'Join the chapter for the country where your business is based — Nigeria or Kenya. The application form lets you select the appropriate chapter.'],

            // ── Blog / News ────────────────────────────────────────────────
            ['where can i read your news', 'blog', 'Our latest news, press releases, insights and announcements are on the Blog page. You can browse by category or tag, and subscribe via our RSS feed.'],
            ['do you have a newsletter', 'blog', 'We share updates through our Blog and to members by email. Members automatically receive news; you can also follow the Blog page for the latest posts.'],
            ['how do i follow updates', 'blog', 'Follow the Blog page on our website, subscribe to the RSS feed at /blog/feed, or become a member to receive updates by email.'],

            // ── Account / Login ────────────────────────────────────────────
            ['how do i log in', 'account', 'Members and staff sign in via the Member Login link in the top navigation. Use the email and password from your account. If you have trouble, use the password reset link.'],
            ['i forgot my password', 'account', 'On the login page, click "Forgot password" to receive a secure reset link by email. If you still cannot get in, contact us through the Contact page.'],
            ['what can i do in the member dashboard', 'account', 'Signed-in members can view their profile, membership status, invoices and relevant trade information. Staff have access to the backoffice modules they are permitted to use.'],

            // ── Contact ────────────────────────────────────────────────────
            ['how do i contact you', 'contact', 'The quickest way is the Contact page on our website, where you can send us a message directly. For trade enquiries you can also email trade@nikccima.org.'],
            ['what is your email', 'contact', 'General enquiries: info@nikccima.org. Trade enquiries: trade@nikccima.org. You can also reach leadership via the Contact and Leadership pages.'],
            ['how do i make a complaint', 'contact', 'Please send the details through our Contact page or email info@nikccima.org. The Secretariat will follow up with you.'],
            ['what are your office hours', 'contact', 'Our Secretariat generally operates during standard business hours on weekdays. For the fastest response, send a message via the Contact page and we will reply by email.'],
        ];

        $sort = 0;
        foreach ($faqs as [$question, $category, $answer]) {
            ChatbotFaq::updateOrCreate(
                ['question' => $question],
                [
                    'answer' => $answer,
                    'category' => $category,
                    'is_active' => true,
                    'sort_order' => $sort++,
                ],
            );
        }
    }
}
