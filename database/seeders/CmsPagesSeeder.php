<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class CmsPagesSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'homepage',
                'title' => 'Homepage',
                'is_published' => true,
                'sections' => [
                    'hero_title' => 'Nigeria-Kenya Chamber of Commerce, Industry, Mines & Agriculture',
                    'hero_subtitle' => 'Driving AfCFTA corridor execution between Nigeria and Kenya — trade, investment, and policy, delivered.',
                    'about_heading' => 'About NiKCCIMA',
                    'about_body' => 'The Nigeria-Kenya Chamber of Commerce, Industry, Mines and Agriculture (NiKCCIMA) is a bilateral trade institution established to operationalise the African Continental Free Trade Area (AfCFTA) corridor between Nigeria and Kenya.',
                ],
            ],
            [
                'slug' => 'about',
                'title' => 'About',
                'is_published' => true,
                'sections' => [
                    'hero_title' => 'About NiKCCIMA',
                    'hero_subtitle' => 'The premier bilateral trade chamber for Nigeria-Kenya corridor execution under the AfCFTA.',
                    'mission_heading' => 'Our Mission',
                    'mission_body' => 'To facilitate bilateral trade and investment between Nigeria and Kenya through structured corridor execution under the AfCFTA framework.',
                    'vision_heading' => 'Our Vision',
                    'vision_body' => 'To become the premier bilateral trade chamber driving measurable AfCFTA corridor outcomes across Africa.',
                    'about_body' => '<p>NiKCCIMA was established to bridge the trade gap between Nigeria and Kenya — two of Africa\'s largest economies — by providing institutional structure, governance, and execution capacity for bilateral corridor trade.</p>',
                ],
            ],
            [
                'slug' => 'sectors',
                'title' => 'Sectors',
                'is_published' => true,
                'sections' => [
                    'page_heading' => 'Key Sectors',
                    'page_subtitle' => 'NiKCCIMA operates across four strategic sectors of the AfCFTA corridor.',
                    'maritime_body' => 'Maritime and Blue Economy trade facilitation between Nigerian and Kenyan ports.',
                    'agriculture_body' => 'Agricultural commodity trade and value-chain development across the corridor.',
                    'aviation_body' => 'Aviation and air cargo corridor development for perishable and high-value goods.',
                    'waterways_body' => 'Inland waterways logistics and cross-border trade facilitation.',
                ],
            ],
            [
                'slug' => 'pillars-overview',
                'title' => 'Our Four Pillars',
                'is_published' => true,
                'sections' => [
                    'page_heading' => 'Our Four Pillars',
                    'page_subtitle' => 'NiKCCIMA operates through four structured pillars that drive bilateral trade execution between Nigeria and Kenya.',
                    'pillar1_title' => 'Executive & Institutional Leadership',
                    'pillar1_icon' => '🏛️',
                    'pillar1_summary' => 'Governance, council oversight, and institutional direction for the chamber.',
                    'pillar2_title' => 'Trade, Investment & Business Development',
                    'pillar2_icon' => '📈',
                    'pillar2_summary' => 'Trade corridor activation, B2B facilitation, anchor investor engagement, and deal pipeline management.',
                    'pillar3_title' => 'Policy, Research & Strategic Affairs',
                    'pillar3_icon' => '📋',
                    'pillar3_summary' => 'Non-tariff barrier resolution, policy briefs, rules of origin guidance, and AfCFTA compliance.',
                    'pillar4_title' => 'Administration, Finance & Membership',
                    'pillar4_icon' => '⚙️',
                    'pillar4_summary' => 'Membership services, financial management, secretariat operations, and KPI governance.',
                ],
            ],
            [
                'slug' => 'pillar-executive',
                'title' => 'Executive & Institutional Leadership',
                'is_published' => true,
                'sections' => [
                    'mandate' => 'Provide strategic governance and institutional direction for NiKCCIMA across both chapters.',
                    'responsibilities' => "Global Governing Council oversight\nBilateral policy direction\nStrategic planning and performance review\nExternal institutional partnerships",
                    'programs' => "Annual Bilateral Summit\nQuarterly Governing Council Sessions\nStrategic Plan Execution Framework",
                    'kpis' => "Council meetings held per year\nStrategic objectives achieved\nMoUs signed and active",
                ],
            ],
            [
                'slug' => 'pillar-trade',
                'title' => 'Trade, Investment & Business Development',
                'is_published' => true,
                'sections' => [
                    'mandate' => 'Activate and manage the Nigeria-Kenya trade corridor by connecting businesses, facilitating deals, and attracting anchor investors.',
                    'responsibilities' => "Trade lead generation and matching\nDeal pipeline management\nAnchor investor engagement\nB2B matchmaking facilitation\nCorridor activation programs",
                    'programs' => "Bilateral Trade Mission\nSector-Specific B2B Roundtables\nAnchor Investor Showcase\nAfCFTA Corridor Pilots",
                    'kpis' => "Trade leads generated\nDeals facilitated (value USD)\nCorridor pilots active\nFirms onboarded to corridors",
                ],
            ],
            [
                'slug' => 'pillar-policy',
                'title' => 'Policy, Research & Strategic Affairs',
                'is_published' => true,
                'sections' => [
                    'mandate' => 'Provide evidence-based policy advocacy, resolve non-tariff barriers, and produce strategic research that guides AfCFTA corridor decisions.',
                    'responsibilities' => "NTB identification and escalation\nPolicy brief publication\nRules of origin guidance\nGovernment engagement tracking\nTrade status reporting",
                    'programs' => "NTB Resolution Task Force\nPolicy Brief Series\nRules of Origin Clinics\nAfCFTA Compliance Workshops",
                    'kpis' => "NTBs resolved\nPolicy briefs published\nGovernment engagements logged",
                ],
            ],
            [
                'slug' => 'pillar-admin',
                'title' => 'Administration, Finance & Membership',
                'is_published' => true,
                'sections' => [
                    'mandate' => 'Ensure operational excellence through sound financial management, robust membership services, and efficient secretariat administration.',
                    'responsibilities' => "Membership onboarding and management\nFee collection and financial reporting\nBudget planning and control\nSecretariat operations\nKPI governance and reporting",
                    'programs' => "Member Onboarding Programme\nAnnual Budget Review\nMembership Certificate Issuance\nKPI Scorecard Publication",
                    'kpis' => "Active members by category\nRevenue collected vs budget\nMembership renewals rate",
                ],
            ],
            [
                'slug' => 'trade-investment',
                'title' => 'Trade & Investment',
                'is_published' => true,
                'sections' => [
                    'hero_title' => 'Trade & Investment Portal',
                    'hero_subtitle' => 'Connect with active trade opportunities, anchor investors, and export resources across the Nigeria-Kenya AfCFTA corridor.',
                    'intro_body' => 'NiKCCIMA facilitates structured bilateral trade and investment between Nigeria and Kenya. Browse active trade leads, explore anchor investment opportunities, and access export readiness resources.',
                    'export_resources_heading' => 'Export Readiness Resources',
                    'export_resources_body' => '<p>Access guides, AfCFTA documentation, rules of origin explanations, and compliance downloads to prepare your business for bilateral trade.</p>',
                ],
            ],
            [
                'slug' => 'membership',
                'title' => 'Membership',
                'is_published' => true,
                'sections' => [
                    'hero_title' => 'Join NiKCCIMA',
                    'hero_subtitle' => 'Become a member and access bilateral trade corridors, policy advocacy, structured B2B pipelines, and visibility across Nigeria and Kenya.',
                    'intro_body' => 'NiKCCIMA membership provides direct access to the Nigeria-Kenya AfCFTA trade corridor with structured support across every stage of business development.',
                    'apply_heading' => 'Ready to Apply?',
                    'apply_body' => 'Submit your membership application online. Our team reviews all applications within 5 working days.',
                ],
            ],
            [
                'slug' => 'events-missions',
                'title' => 'Events & Missions',
                'is_published' => true,
                'sections' => [
                    'hero_title' => 'Events & Trade Missions',
                    'hero_subtitle' => 'Join NiKCCIMA flagship events, bilateral trade missions, and sector forums driving measurable corridor outcomes.',
                    'intro_body' => 'NiKCCIMA organises structured events that connect businesses, policymakers, and investors across the Nigeria-Kenya corridor.',
                ],
            ],
            [
                'slug' => 'chapter-nigeria',
                'title' => 'Nigeria Chapter',
                'is_published' => true,
                'sections' => [
                    'heading' => 'Nigeria Chapter',
                    'description' => 'The NiKCCIMA Nigeria Chapter is headquartered in Abuja, Federal Capital Territory, and leads corridor activation from the Nigerian side of the bilateral mandate.',
                    'address' => 'Abuja, Federal Capital Territory, Federal Republic of Nigeria',
                    'phone' => '+234 000 000 0000',
                    'email' => 'nigeria@nikccima.org',
                    'initiatives' => 'Export Readiness Programme, Corridor Pilot Activation, NTB Resolution Task Force, Anchor Investor Engagement',
                ],
            ],
            [
                'slug' => 'chapter-kenya',
                'title' => 'Kenya Chapter',
                'is_published' => true,
                'sections' => [
                    'heading' => 'Kenya Chapter',
                    'description' => 'The NiKCCIMA Kenya Chapter is headquartered in Nairobi and leads corridor activation from the Kenyan side of the bilateral mandate.',
                    'address' => 'Nairobi, Republic of Kenya',
                    'phone' => '+254 000 000 000',
                    'email' => 'kenya@nikccima.org',
                    'initiatives' => 'B2B Matchmaking Programme, AfCFTA Compliance Clinics, Investment Corridor Development, Trade Mission Organisation',
                ],
            ],
            [
                'slug' => 'contact',
                'title' => 'Contact Us',
                'is_published' => true,
                'sections' => [
                    'hero_title' => 'Get in Touch',
                    'nigeria_address' => 'Abuja, Federal Capital Territory, Federal Republic of Nigeria',
                    'nigeria_phone' => '+234 000 000 0000',
                    'nigeria_email' => 'nigeria@nikccima.org',
                    'kenya_address' => 'Nairobi, Republic of Kenya',
                    'kenya_phone' => '+254 000 000 000',
                    'kenya_email' => 'kenya@nikccima.org',
                    'map_embed_url' => '',
                ],
            ],
        ];

        foreach ($pages as $page) {
            CmsPage::firstOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }

        // Merge-safe: add new image/content keys without overwriting existing admin values.
        // array_merge($newKeys, $existing) — existing admin values always take precedence.
        $newKeys = [
            'homepage' => [
                'hero_image' => '',              // Upload 1920×1080 via Admin → CMS → Pages → Homepage
                'feature_image' => '',           // Upload 800×600 — "Why NiKCCIMA" split section (right column)
                'cta_background_image' => '',    // Upload 1920×500 — bottom CTA strip background
                'hero_cta_primary' => 'Become a Member',
                'hero_cta_secondary' => 'Explore Trade Opportunities',
                'about_heading' => 'About NiKCCIMA',
                'about_body' => 'The Nigeria-Kenya Chamber of Commerce, Industry, Mines and Agriculture (NiKCCIMA) is a bilateral trade institution established to operationalise the African Continental Free Trade Area (AfCFTA) corridor between Nigeria and Kenya.',
                'pillar1_title' => 'Executive & Institutional Leadership',
                'pillar1_summary' => 'Governance, council oversight, and institutional direction for the chamber.',
                'pillar2_title' => 'Trade, Investment & Business Development',
                'pillar2_summary' => 'Corridor activation, B2B facilitation, anchor investor engagement, and deal pipeline management.',
                'pillar3_title' => 'Policy, Research & Strategic Affairs',
                'pillar3_summary' => 'NTB resolution, policy briefs, rules of origin guidance, and AfCFTA compliance.',
                'pillar4_title' => 'Administration, Finance & Membership',
                'pillar4_summary' => 'Membership services, secretariat operations, financial management, and KPI governance.',
                'cta_heading' => 'Ready to Join NiKCCIMA?',
                'cta_body' => 'Become part of the governed bilateral trade chamber connecting Nigeria and Kenya under the AfCFTA framework.',
            ],
            'about' => [
                'banner_image' => '',        // Upload 1920×600 via Admin → CMS → Pages → About
                'story_image' => '',         // Upload 800×600 — "Our Story" split section (right column)
                'governance_structure' => '', // Org chart description or HTML
                'hero_title' => 'About NiKCCIMA',
                'hero_subtitle' => 'The premier bilateral trade chamber for Nigeria-Kenya corridor execution under the AfCFTA.',
            ],
            'pillars-overview' => [
                'banner_image' => '',        // Upload via Admin → CMS → Pages → Our Four Pillars → banner_image
            ],
            'pillar-executive' => [
                'banner_image' => '',
            ],
            'pillar-trade' => [
                'banner_image' => '',
            ],
            'pillar-policy' => [
                'banner_image' => '',
            ],
            'pillar-admin' => [
                'banner_image' => '',
            ],
            'trade-investment' => [
                'banner_image' => '',        // Upload 1920×600 via Admin → CMS → Pages → Trade & Investment
                'trade_map_image' => '',     // Upload 1200×600 — Nigeria-Kenya corridor map/visual
            ],
            'membership' => [
                'banner_image' => '',        // Upload 1920×600 via Admin → CMS → Pages → Membership
                'benefits_image' => '',      // Upload 800×600 — membership benefits visual panel
                'platinum_benefits' => "Direct access to bilateral trade corridor\nExclusive B2B matchmaking\nFull summit & mission participation\nGoverning Council observer status\nDedicated account manager",
                'gold_benefits' => "Trade corridor facilitation\nB2B matchmaking\nEvents & summit access\nPolicy brief subscription",
                'silver_benefits' => "Trade directory listing\nEvents & mission access\nMonthly newsletter",
                'bronze_benefits' => "Chamber directory listing\nPublic events access\nAfCFTA updates",
            ],
            'events-missions' => [
                'banner_image' => '',        // Upload via Admin → CMS → Pages → Events & Missions → banner_image
            ],
            'chapter-nigeria' => [
                'banner_image' => '',        // Upload 1920×600 via Admin → CMS → Pages → Nigeria Chapter
                'chapter_photo' => '',       // Upload 800×600 — Nigeria chapter feature photo (office/city)
                'office_hours' => 'Mon–Fri, 8am–5pm WAT',
            ],
            'chapter-kenya' => [
                'banner_image' => '',        // Upload 1920×600 via Admin → CMS → Pages → Kenya Chapter
                'chapter_photo' => '',       // Upload 800×600 — Kenya chapter feature photo (office/city)
                'office_hours' => 'Mon–Fri, 8am–5pm EAT',
            ],
            'contact' => [
                'banner_image' => '',        // Upload via Admin → CMS → Pages → Contact → banner_image
            ],
        ];

        foreach ($newKeys as $slug => $keys) {
            $page = CmsPage::where('slug', $slug)->first();
            if ($page) {
                // Existing admin values take precedence over new defaults
                $page->update(['sections' => array_merge($keys, $page->sections ?? [])]);
            }
        }

        // Remove deprecated keys from existing DB records (safe — only removes dead keys).
        $cleanup = [
            'homepage' => ['hero_cta_label', 'intro_heading', 'intro_body'],
            'about'    => ['about_heading', 'about_body_heading'],
        ];
        foreach ($cleanup as $slug => $deadKeys) {
            $p = CmsPage::where('slug', $slug)->first();
            if ($p) {
                $s = array_diff_key($p->sections ?? [], array_flip($deadKeys));
                $p->update(['sections' => $s]);
            }
        }

        $this->command->info('CMS pages seeded successfully.');
    }
}
