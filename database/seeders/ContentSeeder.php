<?php

namespace Database\Seeders;

use App\Models\HomepageSection;
use App\Models\JournalArticle;
use App\Models\Page;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->pages();
        $this->journal();
        $this->homepage();
    }

    private function pages(): void
    {
        $pages = [
            ['title' => 'Our Story', 'slug' => 'our-story', 'content' => '<p>Human In Motion began in East London with a simple question: why does most menswear ask you to choose between style and functionality?</p><p>The answer is a wardrobe built for movement. Garments with weight, shape and purpose, designed to stay with you through the everyday.</p>'],
            ['title' => 'Delivery', 'slug' => 'delivery', 'content' => '<h2>UK Delivery</h2><p>Standard delivery of £3.95 arrives within 2–4 working days. Orders over £100 ship free.</p><p>Express delivery of £6.95 arrives within 1–2 working days.</p><h2>International Delivery</h2><p>Europe from £12 (5–9 working days). Rest of world from £18 (7–14 working days).</p>'],
            ['title' => 'Returns', 'slug' => 'returns', 'content' => '<p>You have 30 days from delivery to return any item in original condition with tags attached.</p><p>Returns are free within the UK. Once received and inspected, we refund to your original payment method within 5 working days.</p>'],
            ['title' => 'Size Guide', 'slug' => 'size-guide', 'content' => '<p>Our garments are designed to a modern fit. The signature oversized fits wear one size up from your regular size.</p><p>See product pages for specific measurements before purchasing.</p>'],
            ['title' => 'FAQ', 'slug' => 'faq', 'content' => '<h3>How do I track my order?</h3><p>You will receive tracking by email once your order ships.</p><h3>Can I return a sale item?</h3><p>Yes, all items excluding underwear and socks are returnable within 30 days.</p><h3>Where is my order dispatched from?</h3><p>All orders are dispatched from our London warehouse.</p>'],
            ['title' => 'Contact', 'slug' => 'contact', 'content' => '<p>Email us at hello@humaninmotion.co.uk for anything related to an order.</p><p>For wholesale and stockist enquiries email wholesale@humaninmotion.co.uk.</p>'],
            ['title' => 'Careers', 'slug' => 'careers', 'content' => '<p>We are always looking for people who move differently. Send your CV to careers@humaninmotion.co.uk.</p>'],
            ['title' => 'Stockists', 'slug' => 'stockists', 'content' => '<p>Select Human In Motion pieces are available at partner retailers across London, Manchester and Amsterdam.</p>'],
            ['title' => 'Privacy Policy', 'slug' => 'privacy-policy', 'content' => '<p>This privacy policy explains how Human In Motion collects and uses your personal data when you use our website.</p>'],
            ['title' => 'Terms & Conditions', 'slug' => 'terms-conditions', 'content' => '<p>These terms govern your use of the Human In Motion website and the purchase of our products.</p>'],
            ['title' => 'Cookie Policy', 'slug' => 'cookie-policy', 'content' => '<p>We use necessary cookies for the site to function. Optional analytics and marketing cookies are only activated with your consent.</p>'],
            ['title' => 'Accessibility', 'slug' => 'accessibility', 'content' => '<p>Human In Motion is committed to making our website accessible to everyone, including people with disabilities.</p>'],
            ['title' => 'Refund Policy', 'slug' => 'refund-policy', 'content' => '<p>Refunds are issued to the original payment method within 5 working days of a return being received and inspected.</p>'],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(['slug' => $page['slug']], $page + [
                'meta_title' => $page['title'] . ' | Human In Motion',
                'meta_description' => 'Human In Motion — ' . $page['title'] . '.',
                'is_active' => true,
            ]);
        }
    }

    private function journal(): void
    {
        $articles = [
            ['title' => 'The Modern Bomber Returns', 'slug' => 'the-modern-bomber-returns', 'category' => 'Campaign Stories', 'author' => 'HIM Editorial', 'excerpt' => 'Why the varsity bomber is the statement piece of the season, and how to wear it without looking retro.', 'content' => '<p>For a season that asks for confidence, the bomber is back with purpose.</p><h2>Styled with intent</h2><p>Wear it oversized over a heavyweight tee, or sharpened over a knit for evenings.</p>'],
            ['title' => 'A Field Guide to Cargo Trousers', 'slug' => 'a-field-guide-to-cargo-trousers', 'category' => 'Styling Guides', 'author' => 'HIM Editorial', 'excerpt' => 'The cargo trouser was made for movement. Here is how to build around it.', 'content' => '<p>The cargo trouser is utility done right.</p><h2>The build</h2><p>Pair a relaxed cargo with a boxy tee and a clean sneaker for an everyday uniform.</p>'],
            ['title' => 'Designed for Movement', 'slug' => 'designed-for-movement', 'category' => 'Brand Stories', 'author' => 'HIM Editorial', 'excerpt' => 'Inside the thinking behind the Signature Collection and the proportion that defines it.', 'content' => '<p>Every piece in the Signature Collection begins with a single question: how does this move?</p>'],
            ['title' => 'Wardrobe Essentials, Considered', 'slug' => 'wardrobe-essentials-considered', 'category' => 'Fashion Articles', 'author' => 'HIM Editorial', 'excerpt' => 'What makes a truly essential wardrobe, and why weight in fabric matters.', 'content' => '<p>The best essentials are the ones you stop thinking about.</p><h2>Weight</h2><p>Heavier fabrics hold their shape. They drape, they settle, they last.</p>'],
            ['title' => 'Interview: The Fit That Rated First', 'slug' => 'interview-fit-first', 'category' => 'Interviews', 'author' => 'HIM Editorial', 'excerpt' => 'We sat down with our head of design to talk oversized fits and measured proportions.', 'content' => '<p>Oversized is not sack-like. Oversized is engineered line and drape.</p>'],
        ];

        foreach ($articles as $i => $article) {
            JournalArticle::updateOrCreate(['slug' => $article['slug']], $article + [
                'status' => 'published',
                'meta_title' => $article['title'] . ' | Human In Motion Journal',
                'meta_description' => $article['excerpt'],
                'published_at' => now()->subDays($i * 9),
                'gallery' => [],
            ]);
        }
    }

    private function homepage(): void
    {
        $sections = [
            [
                'type' => 'hero',
                'title' => 'HUMAN IN MOTION',
                'description' => 'Designed for those who move differently.',
                'button_text' => 'SHOP NEW IN',
                'button_url' => '/new-in',
                'button_two_text' => 'EXPLORE COLLECTION',
                'button_two_url' => '/collections/signature',
                'text_position' => 'left',
                'overlay_strength' => 55,
                'sort_order' => 1,
                'content' => ['subtext' => 'NEW SEASON — SIGNATURE COLLECTION'],
            ],
            [
                'type' => 'product_carousel',
                'title' => 'NEW ARRIVALS',
                'description' => 'Fresh drops, delivered first.',
                'sort_order' => 2,
                'content' => ['tag' => 'new'],
            ],
            [
                'type' => 'category_grid',
                'title' => 'SHOP THE COLLECTION',
                'sort_order' => 3,
            ],
            [
                'type' => 'product_carousel',
                'title' => 'BEST SELLERS',
                'description' => 'The pieces in permanent rotation.',
                'sort_order' => 4,
                'content' => ['tag' => 'bestsellers'],
            ],
            [
                'type' => 'editorial',
                'title' => 'MOVEMENT IS THE DESIGN',
                'description' => 'A campaign in three parts. Shot across London, the Signature Collection is built around proportion, weight and line.',
                'button_text' => 'READ THE STORY',
                'button_url' => '/journal/designed-for-movement',
                'image_desktop' => '/placeholder/campaign-editorial.svg',
                'text_position' => 'left',
                'sort_order' => 5,
            ],
            [
                'type' => 'collection_tiles',
                'title' => 'SHOP BY COLLECTION',
                'sort_order' => 6,
                'content' => ['collections' => ['signature', 'limited-edition', 'essentials']],
            ],
            [
                'type' => 'promotional_banner',
                'title' => 'LIMITED EDITION',
                'description' => 'Small runs. Never restocked. When they are gone, they are gone.',
                'button_text' => 'SHOP LIMITED',
                'button_url' => '/collections/limited-edition',
                'sort_order' => 7,
            ],
            [
                'type' => 'image_banner',
                'title' => 'SALE',
                'description' => 'Selected styles for the season.',
                'button_text' => 'SHOP SALE',
                'button_url' => '/sale',
                'sort_order' => 8,
            ],
            [
                'type' => 'newsletter',
                'title' => 'JOIN HUMAN IN MOTION',
                'description' => 'Be first to discover new collections, exclusive releases and private offers.',
                'sort_order' => 9,
            ],
        ];

        foreach ($sections as $section) {
            HomepageSection::updateOrCreate(
                ['type' => $section['type'], 'sort_order' => $section['sort_order']],
                $section + ['is_active' => true]
            );
        }
    }
}